function jselHideList() {
    let list = this.children[2];
    if (list.style.display == "none") {
        list.style.display = "";
    }
    else {
        list.style.display = "none";
    }
}

function jselSelect(event) {
    let list = this.parentNode;
    let select = list.parentNode;
    let block = select.children[0].children[0];
    let input = select.children[1];
    for (i = 0; i < list.children.length; i++) {
        list.children[i].classList.remove('jsel__option__selected');
    }
    this.classList.add("jsel__option__selected");
    block.innerHTML = this.innerHTML;
    input.value = this.getAttribute('value');
    console.log(input.value);
}
function createSelect(selector) {
    select = document.querySelectorAll(selector);


    select.forEach(element => {
        if (!element.multiple) {
            input = document.createElement("input");
            input.name = element.name;
            block = document.createElement("div");
            block.classList.add('jsel__hidden__block');
            text = document.createElement("div");
            arrow = document.createElement("div");
            arrow.innerHTML = "▼";
            text.classList.add('jsel__hidden__block__text')
            arrow.classList.add('jsel__hidden__block__arrow')
            block.appendChild(text)
            block.appendChild(arrow)
            input.type = "hidden";
            wrapper = document.createElement('div');
            wrapper.classList.add('jsel__list');
            wrapper.style = "display:none;";
            element.classList.add('jsel__select');
            element.addEventListener('click', jselHideList);
            input.value = element.children[0].getAttribute('value');
            element.children[0].classList.add("jsel__option__selected");
            text.innerHTML = element.children[0].innerHTML;
            count = element.children.length
            for (var i = 0; i < count; i++) {
                el = element.children[0];
                wrapper.appendChild(el);
                el.classList.add('jsel__option')
            }
            for (i = 0; i < wrapper.children.length; i++) {
                wrapper.children[i].addEventListener('click', jselSelect);
            }
            element.appendChild(wrapper, element);
            element.insertBefore(input, element.children[0]);
            element.insertBefore(block, element.children[0]);
        }
        else{
            count = element.children.length
            for (var i = 0; i < count; i++) {
                el = element.children[0];
                
                inp = document.createElement('input');
                inp.type = "checkbox";
                inp.name = element.name;
                inp.value = el.getAttribute('value');
                inp.style = "display: contents;"
                wrapper = document.createElement('label');
                
                
                wrapper.appendChild(inp);
                wrapper.appendChild(el);
                el.classList.add('jsel__option');
                el.addEventListener('click',e=>{
                    console.log();
                    if(!e.target.previousSibling.checked){
                        e.target.classList.add("jsel__option__selected");
                    }
                    else{
                        e.target.classList.remove("jsel__option__selected");
                    }
                })
                element.appendChild(wrapper);
                
            }
        }
    });
    return select;
}