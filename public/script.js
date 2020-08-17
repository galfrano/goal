function plusMinus(){

	this.children = document.getElementById('child-table').getElementsByTagName('tr').length-1;
	this.toDelete = 0;
	var self = this;
	var	span = document.getElementsByTagName('span');

	for(var x in span){
		if(span[x].className && span[x].className.indexOf('minus') > -1){
			span[x].onclick = function(){
				self.minus(this.parentNode.parentNode);
			}
		}
	}
	this.indexChildren = function(tag, row){
		var elements = row.getElementsByTagName(tag);
		for(var x = 0; x<elements.length; x++){
			elements[x].name = elements[x].name.replace('[]', '['+this.children+']');
		}
	}
	this.addChild = function(){
		var row = document.getElementById('def-row').cloneNode(true);
		row.removeAttribute('id');
		var fields = ['select', 'textarea', 'input'];
		for(var x in fields){
			this.indexChildren(fields[x], row);
		}
		document.getElementById('child-table').getElementsByTagName('tbody')[0].appendChild(row);
		row.getElementsByTagName('span')[0].onclick = function(){
			self.minus(this.parentNode.parentNode);
		}
		this.children++;
	}
	this.minus = function(tableRow){
		for(var inputs = tableRow.getElementsByTagName('input'), l = inputs.length, x = 0; x<l; x++){
			if(inputs[x].type == 'hidden'){
				var tr = document.createElement('tr');
				var td = document.createElement('td');
				var input = document.createElement('input');
				input.name = inputs[x].name.replace(/\[\d+\]/, '['+(--self.toDelete)+']');
				input.value = inputs[x].value;
				input.type = 'hidden';
				tr.className = 'del';
				td.appendChild(input);
				tr.appendChild(td);
				tableRow.parentNode.appendChild(tr);
			}
		}
		tableRow.remove();
	}
}

window.onload = function(){
	var plus, lang;
	if(plus = document.getElementById('plus')){
		var pm = new plusMinus;
		plus.onclick = function(){
			pm.addChild();
		}
	}
	if(lang = document.getElementById('chlang')){
		lang.onchange = function(){
			document.forms['changeLanguage'].submit();
		}
	}
}
