function handleDragStart(e) {
  this.style.opacity = '0.4';  // this / e.target is the source node.
}


function handleDragOver(e) {
  if (e.preventDefault) {
    e.preventDefault(); // Necessary. Allows us to drop.
  }

  e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

  return false;
}

function handleDragEnter(e) {
  // this / e.target is the current hover target.
  this.classList.add('over');
}

function handleDragLeave(e) {
  this.classList.remove('over');  // this / e.target is previous target element.
}





var cols = document.querySelectorAll('aside#pluginlist div.plugin');
[].forEach.call(cols, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
});

var droptarget = document.querySelectorAll('section#activeplugins');
[].forEach.call(droptarget, function(area) {
	area.addEventListener('dragenter', handleDragEnter, false);
	area.addEventListener('dragover', handleDragOver, false);
	area.addEventListener('dragleave', handleDragLeave, false);
});
