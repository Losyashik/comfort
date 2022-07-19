<template>
  <div>
    <label>
      <div
        id="drop"
        @dragover.prevent="highlight()"
        @dragenter.prevent="highlight()"
        @dragleave.prevent="unhighlight()"
        @drop.prevent="dropImg($event)"
      >
        <div v-if="images.length" id="images">
          <div class="block" v-for="(src, index) in images" :key="index">
            <img :src="src" class="image" alt="" />
            <div class="delete" @click.prevent>
              <img
                @click="deleteItem(index)"
                src="./../../../assets/images/interface/close.png"
                alt=""
              />
            </div>
          </div>
        </div>
        <div v-else class="prew">Нажмите или перетащите изображение</div>
      </div>
      <input
        type="file"
        name="images[]"
        id="dropInp"
        @change="prewImg()"
        style="display: hidden"
        :multiple="multiple"
      />
    </label>
  </div>
</template>
<script>
export default {
  data() {
    return {
      images: [],
    };
  },
  methods: {
    highlight() {
      let dropArea = document.querySelector("#drop");
      dropArea.classList.add("highlight");
    },
    unhighlight() {
      let dropArea = document.querySelector("#drop");
      dropArea.classList.remove("highlight");
    },
    prewImg() {
      this.images = [];
      let images = document.querySelector("#dropInp").files;
      [...images].forEach((file) => {
        let reader = new FileReader();
        var that = this;
        reader.onload = function (e) {
          that.images.push(e.target.result);
        };
        reader.readAsDataURL(file);
      });
    },
    dropImg(e) {
      this.unhighlight();
      let inp = document.querySelector("#dropInp");
      let inpFileList = inp.files;
      let newFileList = new DataTransfer();

      let addedFiles = e.dataTransfer.files;

      for (var i = 0; i < inpFileList.length; i++) {
        newFileList.items.add(inpFileList[i]);
      }

      [...addedFiles].forEach((file) => {
        newFileList.items.add(file);
      });
      inp.files = newFileList.files;
      this.prewImg();
    },
    deleteItem(index) {
      let inp = document.querySelector("#dropInp");
      let inpFileList = inp.files;
      let newFileList = new DataTransfer();
      for (var i = 0; i < inpFileList.length; i++) {
        if (i != index) newFileList.items.add(inpFileList[i]);
      }
      inp.files = newFileList.files;

      this.prewImg();
    },
  },
  props: ["multiple"],
};
</script>
<style scoped>
input {
  display: none;
}
label {
  display: block;
}
#drop {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 150px;
  border: 1px solid;
}
.highlight {
  background: #0c0;
}
#images {
  overflow: auto;
  max-height: 140px;
  padding: 5px;
  display: flex;
  flex-wrap: wrap;
}
#images .block {
  position: relative;
  width: 140px;
  height: 140px;
  overflow: hidden;
}
#images .block .delete {
  width: 140px;
  height: 140px;
  position: absolute;
  top: 140px;
  transition: top 0.2s;
  left: 0;
}
#images .block .delete img {
  width: 15px;
  height: 15px;
  position: absolute;
  top: 2px;
  right: 2px;
}
#images .block:hover .image {
  filter: opacity(50%);
}
#images .block:hover .delete {
  top: 0;
}
#images .block img {
  width: 140px;
  height: 140px;
}
</style>
