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


function recipe_form_check(){
   
   var ing_table = document.getElementById('rp_ingredients_body'); 
   var ing_table_children = ing_table.rows;
   
    for(var i = 0; i < ing_table_children.length; i++) {
        id =  ing_table_children.item(i).id;        
        id_number = id.substring(14);  //rp_ingredient_
        if(id_number=='null') continue;
        txt_value = document.getElementById('ingname_'+id_number).value;
        if(txt_value != ''){ 
           document.getElementById('ing_value').value = txt_value;
           break;
        }
    } 
    
   
   var ing_table = document.getElementById('rp_instructions_body'); 
   var ing_table_children = ing_table.rows;
   
    for(var i = 0; i < ing_table_children.length; i++) {
        id =  ing_table_children.item(i).id;        
        id_number = id.substring(15);  //rp_instruction_
        if(id_number=='null') continue;
        txt_value = document.getElementById('insname_'+id_number).value;
        if(txt_value != ''){ 
           document.getElementById('ins_value').value = txt_value;
           break;
        }
    } 
    
    return true;                   
}