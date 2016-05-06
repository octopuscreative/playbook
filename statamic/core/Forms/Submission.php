<?php

namespace Statamic\Forms;

use Carbon\Carbon;
use Statamic\API\Storage;
use Statamic\Exceptions\PublishException;
use Statamic\Exceptions\HoneypotException;
use Statamic\Contracts\Forms\Submission as SubmissionContract;

class Submission implements SubmissionContract
{
    /**
     * @var bool
     */
    private $guard = true;

    /**
     * @var string
     */
    private $id;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var array
     */
    private $data = [];

    /**
     * Get or set the ID
     *
     * @param mixed|null
     * @return mixed
     */
    public function id($id = null)
    {
        if (is_null($id)) {
            return $this->id ?: time();
        }

        $this->id = $id;
    }

    /**
     * Get or set the form
     *
     * @param Form|null $form
     * @return Form
     */
    public function form($form = null)
    {
        if (is_null($form)) {
            return $this->form;
        }

        $this->form = $form;
    }

    /**
     * Get the formset
     *
     * @return Formset
     */
    public function formset()
    {
        return $this->form()->formset();
    }

    /**
     * Get the fields in the formset
     *
     * @return array
     */
    public function fields()
    {
        return $this->formset()->fields();
    }

    /**
     * Get or set the columns
     *
     * @return array
     */
    public function columns()
    {
        return $this->formset()->columns();
    }

    /**
     * Get the date when this was submitted
     *
     * @return Carbon
     */
    public function date()
    {
        return Carbon::createFromTimestamp($this->id());
    }

    /**
     * Get the date, formatted by what's specified in the formset
     *
     * @return string
     */
    public function formattedDate()
    {
        return $this->date()->format(
            $this->formset()->get('date_format', 'Y-m-d H:m')
        );
    }

    /**
     * Disable validation
     */
    public function unguard()
    {
        $this->guard = false;
    }

    /**
     * Enable validation
     */
    public function guard()
    {
        $this->guard = true;
    }

    /**
     * Get or set the data
     *
     * @param array|null $data
     * @return array
     * @throws PublishException|HoneypotException
     */
    public function data($data = null)
    {
        if (is_null($data)) {
            return $this->data;
        }

        // If a honeypot exists, throw an exception.
        if (array_get($data, $this->formset()->get('honeypot', 'honeypot'))) {
            throw new HoneypotException;
        }

        if ($this->guard) {
            $this->validate($data);

            // Remove any fields that aren't present in the formset.
            $data = array_intersect_key($data, array_flip(array_keys($this->fields())));
        }

        $this->data = $data;
    }

    /**
     * Validate an array of data against rules in the formset
     *
     * @param  array $data       Data to validate
     * @throws PublishException  An exception will be thrown if it doesn't validate
     */
    private function validate($data)
    {
        $rules = [];
        $attributes = [];

        // Merge in field rules
        foreach ($this->fields() as $field_name => $field_config) {
            if ($field_rules = array_get($field_config, 'validate')) {
                $rules[$field_name] = $field_rules;
            }

            // Define the attribute (friendly name) so it doesn't appear as field.fieldname
            $attributes[$field_name] = array_get($field_config, 'display', $field_name) . ' field';
        }

        $validator = app('validator')->make($data, $rules, [], $attributes);

        if ($validator->fails()) {
            $e = new PublishException;
            $e->setErrors($validator->errors()->toArray());
            throw $e;
        }
    }

    /**
     * Get a value of a field
     *
     * @param  string $key
     * @return mixed
     */
    public function get($field)
    {
        return array_get($this->data(), $field);
    }

    /**
     * Save the submission
     */
    public function save()
    {
        $filename = 'forms/' . $this->formset()->name() . '/' . $this->id();

        Storage::putYAML($filename, $this->data());
    }

    /**
     * Delete this submission
     */
    public function delete()
    {
        Storage::delete($this->getPath());
    }

    /**
     * Get the path to the file
     *
     * @return string
     */
    public function getPath()
    {
        return 'forms/' . $this->formset()->name() . '/' . $this->id() . '.yaml';
    }

    /**
     * Convert to an array
     *
     * @return array
     */
    public function toArray()
    {
        $data = $this->data();
        $data['date'] = $this->date();
        $fields = $this->formset()->fields();
        $field_names = array_keys($fields);

        // Populate the missing fields with empty values.
        foreach ($field_names as $field) {
            $data[$field] = array_get($data, $field);
        }

        // Ensure the array is ordered the same way the fields are.
        $data = array_merge(array_flip($field_names), $data);

        return $data;
    }
}
