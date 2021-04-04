<template>
    <div class="contents">
        <div class="col-12 mb-3">
            <h5 class="pb-1 border-rgba(0, 0, 0, 0.125) border-bottom h5">コース名：{{ corse.name }}</h5>
        </div>
        <div class="col-md-6">
            <div class="dropzone-field" id="upload_image" @dragenter="dragEnter" @dragleave="dragLeave" @dragover.prevent @drop.prevent="dropFile" :class="{enter: isEnter, drop: isDrop}">
              <p v-if="!preview">ファイルをアップロード</p>
              <div v-if="preview" style="height:100%">
                <img :src="preview" style="height:100%">
              </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="new-preview-item">

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
        },
        props: {
            corse: {},
        },
        data() {
            return {
              isEnter : false,
              isDrop : false,
              file: null,
              preview: '',
            };
        },
        methods: {
          dragEnter() {
            this.isEnter = true;
          },
          dragLeave() {
            this.isEnter = false;
          },
          // 通常、ファイルをドロップしたときにはブラウザがドロップされた画像を表示するような動きをするが、
          // @drop.preventとすることでその機能を止めている。
          dropFile(event) {
            const files = event.dataTransfer.files;
            this.file = files[0];
            this.isEnter = false;
            this.isDrop = true;
            const reader = new FileReader();
            reader.onload = (e) => {
              this.preview = e.target.result
            }
            reader.readAsDataURL(this.file);
            document.getElementById("upload_image").files = files;
          },
          resetImage() {
            this.file = null;
            this.dataUrl = null;
          },
        },
    };
</script>

<style>
    .dropzone-field {
      color: gray;
      font-weight: bold;
      font-size: 1.2em;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 500px;
      height: 300px;
      border: 1px solid rgba(0, 0, 0, 0.125);
      border-radius: 15px;
    }
    .enter {
      border: 5px dotted #38c172;
    }
    .drop {
      justify-content: start;
      border: none;
    }
</style>