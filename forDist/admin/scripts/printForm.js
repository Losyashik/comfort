window.onload = function () {
  document.querySelector("button.delete").onclick = async (e) => {
    e.preventDefault();
    dataPOST = new FormData(e.target.parentNode.parentNode);
    dataPOST.append("table_name", e.target.value);
    let response = await fetch("./backend/deleteData.php", {
      method: "POST",
      body: dataPOST,
    });
    if (response.ok) {
      alert("Удалено");
      formData = [];
      counter_num = 0;
      post(formData, "./backend/formList.php", data, createList);
    }
  };
};
function priceEdit(e, type) {
  e.preventDefault();
  formData = [];
  data = new FormData(e.target.parentNode.parentNode);

  data = {
    type: type,
    id: data.get("item[]"),
  };
  formData = [];
  post(formData, "./backend/formData.php", data, createForm);
}
var counter_num = 0;
function dropImage(dropArea, dropInp, prevArea) {
  dropInp.addEventListener("change", (e) => {
    readURL(dropInp, prevArea);
  });
  ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, preventDefaults, false);
  });
  n.addEventListener("drop", handleDrop, false);
  ["dragenter", "dragover"].forEach((eventName) => {
    dropArea.addEventListener(eventName, highlight, false);
  });
  ["dragleave", "drop"].forEach((eventName) => {
    dropArea.addEventListener(eventName, unhighlight, false);
  });
  dropArea.addEventListener("drop", handleDrop, false);
  function highlight(e) {
    dropArea.classList.add("highlight");
  }
  function unhighlight(e) {
    dropArea.classList.remove("highlight");
  }
  function handleDrop(e) {
    let inputFileList = dropInp.files;
    let fileList = new DataTransfer();
    for (var i = 0; i < inputFileList.length; i++) {
      fileList.items.add(inputFileList[i]);
    }
    let dt = e.dataTransfer;
    let files = dt.files;
    [...files].forEach((file) => {
      fileList.items.add(file);
    });
    dropInp.files = fileList.files;
    readURL(dropInp, prevArea);
  }
  function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }
  function readURL(input, block) {
    block.innerHTML = "";
    if (input.files.length != 0) {
      console.log(1);
      block.classList.add("images");
      [...input.files].forEach((file) => {
        reader = new FileReader();
        reader.onload = function (e) {
          img = document.createElement("img");
          img.src = e.target.result;
          block.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    } else {
      block.classList.remove("images");
      console.log(0);
      drop = document.createElement("div");
      drop.classList.add("drop");
      block.appendChild(drop);
      text = document.createElement("div");
      text.innerHTML = "Кликните или перетащите сюда изображение";
      drop.appendChild(text);
    }
  }
}
async function post(list, path, data, fun) {
  const f = await fetch(path, {
    method: "POST",
    body: JSON.stringify(data),
  });
  const text = await f.json();
  text.forEach((el) => {
    list.push(el);
  });
  fun(list);
}
function counter(bool) {
  if (bool) {
    counter_num += 1;
  } else {
    counter_num -= 1;
  }
  if (counter_num >= 2) {
    but = document.querySelector("button.edit");
    but.setAttribute("disabled", "");
  } else {
    but = document.querySelector("button.edit");
    but.removeAttribute("disabled");
  }
}
function openForm(e, type, product = "") {
  h2 = document.querySelector("#head");
  edit = document.querySelector("button.edit");
  del = document.querySelector("button.delete");
  edit.removeAttribute("onclick");
  del.removeAttribute("disabled");
  switch (product) {
    case "plinth": {
      edit.value = product + "_" + type;
      del.value = product + "_" + type;
      h2.innerHTML = "Плинтуса " + e.target.innerHTML.toLowerCase();
      break;
    }
    case "linoleum": {
      edit.value = product + "_" + type;
      del.value = product + "_" + type;
      h2.innerHTML = "Линолеум " + e.target.innerHTML.toLowerCase();
      break;
    }

    default: {
      edit.value = type;
      del.value = type;
      h2.innerHTML = e.target.innerHTML;
    }
  }
  if (type == "price" || type == "allowances") {
    h2.innerHTML = "Линолеум " + e.target.innerHTML.toLowerCase();

    data = {
      type: type,
      product: product,
    };
    formData = [];
    counter_num = 0;
    post(formData, "./backend/formList.php", data, createList);

    del.setAttribute("disabled", "true");
    main = document.querySelector(".main");
    main.style = "display:none";
    form = document.querySelector(".form_block");
    form.style = "display:block";

    form = document.querySelector(".form > form > table > tbody");
    form.innerHTML = "";

    edit.setAttribute("onclick", "priceEdit(event, '"+type+"')");
    return;
  }

  data = {
    type: type,
    product: product,
  };
  formData = [];
  post(formData, "./backend/formData.php", data, createForm);
  formData = [];
  counter_num = 0;
  post(formData, "./backend/formList.php", data, createList);
}
function createForm(formData) {
  main = document.querySelector(".main");
  main.style = "display:none";
  form = document.querySelector(".form_block");
  form.style = "display:block";
  form = document.querySelector(".form > form > table > tbody");
  form.innerHTML = "";
  formData.forEach((element) => {
    row = document.createElement("tr");
    row.classList.add("row");
    n = document.createElement("td");
    row.appendChild(n);
    switch (element.tag) {
      case "input":
        inp = document.createElement("input");
        inp.type = element.type;
        switch (element.type) {
          case "file": {
            n.classList.add("file__upload__block");
            n.setAttribute("colspan", "2");
            inp.multiple = element.multiple;
            inp.accept = "image/*";
            inp.name = "images[]";
            inp.id = "images";
            label = document.createElement("label");
            label.setAttribute("for", "images");
            prev = document.createElement("div");
            prev.classList.add("preview");
            label.appendChild(prev);
            drop = document.createElement("div");
            drop.classList.add("drop");
            prev.appendChild(drop);
            text = document.createElement("div");
            text.innerHTML = "Кликните или перетащите сюда изображение";
            drop.appendChild(text);
            n.appendChild(inp);
            n.appendChild(label);
            dropImage(label, inp, prev);
            break;
          }
          case "hidden": {
            inp.name = element.tag_name;
            inp.value = element.value;
            form.appendChild(inp);
            break;
          }
          case "text": {
            n.classList.add("name");
            inp.name = element.tag_name;
            n.innerHTML = element.name;
            if (typeof element["value"] !== "undefined") {
              inp.value = element.value;
            }
            ceil = document.createElement("td");
            ceil.classList.add("ceil");
            row.appendChild(ceil);
            ceil.appendChild(inp);
            break;
          }
        }
        break;
      case "select":
        n.classList.add("name");
        n.innerHTML = element.name;
        ceil = document.createElement("td");
        ceil.classList.add("ceil");
        row.appendChild(n);
        row.appendChild(ceil);
        let select = document.createElement("div");
        select.name = element.tag_name;
        select.multiple = element.multiple;
        select.classList.add("select");
        element.options.forEach((el) => {
          option = document.createElement("div");
          option.classList.add("option");
          option.setAttribute("value", el.id);
          option.innerHTML = el.name;
          select.appendChild(option);
        });
        ceil.appendChild(select);
        break;
      case "button":
        n.setAttribute("colspan", "2");
        n.classList.add("button");
        but = document.createElement("button");
        if (element.tag_name == "price" || element.tag_name == "linoleum_allowances") {
          but.innerHTML = "Изменить";
        } else {
          but.innerHTML = "Добавить";
        }

        but.name = "tableDB";
        but.value = element.tag_name;
        but.onclick = async (e) => {
          e.preventDefault();
          dataPOST = new FormData(document.querySelector(".form form"));
          dataPOST.append("table_name", e.target.value);
          let response = await fetch("./backend/addData.php", {
            method: "POST",
            body: dataPOST,
          });
          if (response.ok) {
            formData = [];
            post(formData, "./backend/formData.php", data, createForm);
            formData = [];
            counter_num = 0;
            post(formData, "./backend/formList.php", data, createList);
          }
        };
        n.appendChild(but);
        break;
    }
    form.appendChild(row);
  });
  createSelect(".select");
}
function createList(data) {
  list = document.querySelector(".form_list");
  list.innerHTML = "";

  data.forEach((el) => {
    label = document.createElement("label");

    input = document.createElement("input");
    input.type = "checkbox";
    input.name = "item[]";
    input.value = el.id;

    div = document.createElement("div");
    div.classList.add("name");
    div.innerHTML = el.name;
    div.addEventListener("click", (e) => {
      e.preventDefault();
      console.log();
      if (!e.target.previousSibling.checked) {
        e.target.previousSibling.checked = true;
        e.target.classList.add("jsel__option__selected");
        counter(true);
      } else {
        e.target.previousSibling.checked = false;
        e.target.classList.remove("jsel__option__selected");
        counter(false);
      }
    });

    label.appendChild(input);
    label.appendChild(div);
    list.appendChild(label);
  });
}
