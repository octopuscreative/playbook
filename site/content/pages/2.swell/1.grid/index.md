content_builder:
  - 
    type: markdown
    content: How to grid.
    md_content: |
      Grids are composed of three main items: containers, rows, and columns. Columns occupy a percentage width of a column that is fluid up until a maximum width. Rows are used to break into new lines without having to add blank columns.
      
      `.container`<br>
      
      `.row` also applies a clearfix for the columns within them. So if you find your columns aren't affecting the height of their parent, the parent likely needs `class="row"`.<br>
      
      `.col-[ # of columns ]`<br>
      
      `.col-md-[ # of columns ]`<br>
  - 
    type: html
    html: |
      <div class="row mln-1 mb-2">
        <div class="col-6  pl-1"><div class="py-2 b-6 cbgg-8"></div></div>
        <div class="col-6  pl-1"><div class="py-2 b-6 cbgg-8"></div></div>
      </div>
      <div class="row mln-1">
        <div class="col-4  pl-1"><div class="py-2 b-6 cbgg-8"></div></div>
        <div class="col-4  pl-1"><div class="py-2 b-6 cbgg-8"></div></div>
        <div class="col-4  pl-1"><div class="py-2 b-6 cbgg-8"></div></div>
      </div>
  - 
    type: markdown
    md_content: "You can use columns without a container (they're agnostic to their context). This is particularly useful if you need to split content within a column."
  - 
    type: html
    html: |
      <div class="row mln-1 mb-2">
      
        <div class="col-6  pl-1">
          <div class="p-2 b-7 cbgg-8">
            <div class="row mln-1">
              <div class="col-6 pl-1">
                <div class="py-2 b-7 cbgg-7"></div>
               </div>
              <div class="col-6 pl-1">
                <div class="py-2 b-7 cbgg-7"></div>
               </div>
            </div>
          </div>
        </div>
      
        <div class="col-6  pl-1">
          <div class="p-2 b-7 cbgg-8">
            <div class="row mln-1">
              <div class="col-4 pl-1">
                <div class="py-2 b-7 cbgg-7"></div>
               </div>
              <div class="col-4 pl-1">
                <div class="py-2 b-7 cbgg-7"></div>
               </div>
              <div class="col-4 pl-1">
                <div class="py-2 b-7 cbgg-7"></div>
               </div>
          </div>
        </div>
      </div>
title: Grid
id: 930d235e-dd01-48ac-aa6f-3a0640a11e70
