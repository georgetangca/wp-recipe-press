// JavaScript methods for the Recipe Form for RecipePress.

function rp_add_ingredient(type) {
     var table = document.getElementById('rp_ingredients_body');
     var rows = table.rows.length;
     var row = table.insertRow(rows);
     row.id = 'rp_ingredient_' + rows;
     row.vAlign = 'top';
     var cellCount = 0;

     // Insert drag cell
     var dragCell = document.createElement('th');
     dragCell.className = 'recipe-press-header-public recipe-press-header-sort';
     controls = document.getElementById('rp_drag_icon').innerHTML;
     dragCell.innerHTML = controls.replace(/NULL/gi, rows).replace(/COPY/gi, '');
     row.appendChild(dragCell);
     ++cellCount;
    
     // Insert Quantity Cell
    
     var quantityCell = row.insertCell(cellCount);
     var quantity = document.createElement('input');
     quantity.type = 'text';
     quantity.name = 'ingredients[' + rows + '][quantity]';
     quantity.className = 'recipe-press-quantity';
     quantityCell.appendChild(quantity);
     ++cellCount;

     // Insert Size Cell
     var sizeCell = row.insertCell(cellCount);
     var contents = document.getElementById('rp_size_column').innerHTML;
     sizeCell.innerHTML = contents.replace(/NULL/gi, rows).replace(/COPY/gi, '');
     ++cellCount;
    
     // Insert item cell
     var itemCell = row.insertCell(cellCount);
     var contents = document.getElementById('rp_item_column').innerHTML;
     itemCell.innerHTML = contents.replace(/NULL/gi, rows).replace(/COPY/gi, '');
     ++cellCount;

     /* Depricated in version 2.0
     // Insert notes cell
     var notes = document.createElement('input');
     notes.type = 'text';
     notes.name = 'ingredients[' + rows + '][notes]';
     if (type == 'admin') {
          notes.className = 'recipe-press-ingredients-notes';
     } else {
          notes.className = 'recipe-press-ingredients-notes-public';
     }
     itemCell.appendChild(notes);*/

   

     table.appendChild(row);
     setup_autocomplete_ingredient();
}

function rp_add_divider(type) {
     var table = document.getElementById('rp_ingredients_body');
     var rows = table.rows.length;
     var row = table.insertRow(rows);
     row.id = 'rp_ingredient_' + rows;
     row.className = 'rp_size_type_divider';
     row.vAlign = 'top';
     var cellCount = 0;

     // Insert drag cell
     var dragCell = document.createElement('th');
     dragCell.className = 'recipe-press-header-public recipe-press-header-sort';
     controls = document.getElementById('rp_drag_icon').innerHTML;
     dragCell.innerHTML = controls.replace(/NULL/gi, rows).replace(/COPY/gi, '');
     row.appendChild(dragCell);
     ++cellCount;
    
     // Insert Quantity Cell
     var quantityCell = row.insertCell(cellCount);
     quantityCell.style.width = '60px';
     ++cellCount;

     // Insert Size Cell
     var sizeCell = row.insertCell(cellCount);
     var item = document.createElement('input');
     item.type = 'text';
     item.name = 'ingredients[' + rows + '][size]';
     item.value = 'divider';
     item.style.width = '55px';
     item.readOnly = true;
     sizeCell.appendChild(item);
     ++cellCount;

     // Insert Item cell
     var itemCell = row.insertCell(cellCount);
     item = document.createElement('input');
     item.type = 'text';
     item.name = 'ingredients[' + rows + '][new-ingredient]';
     if (type == 'admin') {
          item.className = 'recipe-press-ingredients';
     } else {
          item.className = 'recipe-press-ingredients-public';
     }
     itemCell.appendChild(item);
     ++cellCount;

     // Insert Page Cell
     if (type == 'admin') {
          var pageCell = row.insertCell(cellCount);
     }

     table.appendChild(row);
}


function rp_delete_row(elem) {
     if (confirm('You are about to delete this ingredient row, are you sure?') ) {
          var tableRow = document.getElementById(elem);
          var row = tableRow.rowIndex - 1;
          document.getElementById('rp_ingredients_body').deleteRow(row);
     }
}


function clear_ingredient_id(id) {
     var idField = document.getElementById('recipe_ingredient_' + id);
     idField.value= 0;
}

function setup_autocomplete_ingredient() {
     try {
          jQuery(".recipe-item-lookup").suggest("/wp-admin/admin-ajax.php?action=ingredient_lookup", {
               minChars: 2,
               onSelect: function() {
                    data = this.value.split(':');
                    field = this.id.split('_');

                    var idField = document.getElementById('recipe_ingredient_' + field[1]);
                    idField.value = eval(data[1]);
                    this.value = data[0];
               }
          })
     } catch (error) {}
}

/* Set up the ingredient sorting. */
try {
     jQuery(document).ready(function(){
          try {
              
               jQuery("#rp_ingredients tbody").sortable({
                    helper: fixHelper,
                    cursor: 'crosshair',
                    items: 'tr',
                    axis: 'y',
                    distance:  15
               });
          } catch(error) {}

          setup_autocomplete_ingredient();
     });

} catch(error) {}

var fixHelper = function(e, ui) {
     ui.children().each(function() {
          jQuery(this).width(jQuery(this).width());
     });
     return ui;
};


//**************************
//* Big Notes Here: below function can be optimized with above functionns, just past different object id ,like ingrident, instruction 
//add instruction operation
function rp_add_instruction(type) {
     var table = document.getElementById('rp_instructions_body');
     var rows = table.rows.length;
     var row = table.insertRow(rows);
     row.id = 'rp_instruction_' + rows;
     row.vAlign = 'top';
     var cellCount = 0;

     // Insert drag cell
     var dragCell = document.createElement('th');
     dragCell.className = 'recipe-press-header-public recipe-press-header-sort';
     controls = document.getElementById('rp_instruction_drag_icon').innerHTML;
     dragCell.innerHTML = controls.replace(/NULL/gi, rows).replace(/COPY/gi, '');
     row.appendChild(dragCell);
     ++cellCount;
    
    
     // Insert item cell
     var itemCell = row.insertCell(cellCount);
     var contents = document.getElementById('rp_instruction_item_column').innerHTML;
     itemCell.innerHTML = contents.replace(/NULL/gi, rows).replace(/COPY/gi, '');
    
      
     table.appendChild(row);
     setup_autocomplete_instruction();  //what's purpose ?
}

function rp_delete_instruction_row(elem) {
     if (confirm('You are about to delete this instruction row, are you sure?') ) {
          var tableRow = document.getElementById(elem);
          var row = tableRow.rowIndex - 1;
          document.getElementById('rp_instructions_body').deleteRow(row);
     }
}


function clear_instruction_id(id) {
     var idField = document.getElementById('recipe_instruction_' + id);
     idField.value= 0;
}

function setup_autocomplete_instruction() {
     try {
          jQuery(".recipe-item-lookup").suggest("/wp-admin/admin-ajax.php?action=instruction_lookup", {
               minChars: 2,
               onSelect: function() {
                    data = this.value.split(':');
                    field = this.id.split('_');

                    var idField = document.getElementById('recipe_instruction_' + field[1]);
                    idField.value = eval(data[1]);
                    this.value = data[0];
               }
          })
     } catch (error) {}
}

/* Set up the ingredient sorting. */
try {
     jQuery(document).ready(function(){
          try {
              
               jQuery("#rp_instructions tbody").sortable({
                    helper: fixHelper,
                    cursor: 'crosshair',
                    items: 'tr',
                    axis: 'y',
                    distance:  15
               });
          } catch(error) {}

          setup_autocomplete_instruction();
     });

} catch(error) {}
