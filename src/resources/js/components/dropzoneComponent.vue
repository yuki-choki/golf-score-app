<template>
    <div class="contents">
        <div class="col-12 mb-3">
            <h5 class="pb-1 border-rgba(0, 0, 0, 0.125) border-bottom h5">コース名：{{ corse.name }}</h5>
        </div>
        <div class="col-md-6">
            <div
                class="dropzone-field"
                id="upload_image"
                @dragenter="dragEnter"
                @dragleave="dragLeave"
                @dragover.prevent
                @drop.prevent="dropFile"
                :class="{enter: isEnter, drop: isDrop}"
            >
                <p v-if="!preview">ファイルをアップロード</p>
                <div v-if="preview" style="height:100%">
                    <img :src="preview" style="height:100%">
                </div>
            </div>
            <p v-if="imageError">
                <span style="color: red;">{{ errorMessage }}</span>
            </p>
        </div>
        <div class="col-12 my-3">
            <button type="submit" :class="buttonClass" :disabled="disabled">読込開始</button>
        </div>
    </div>
</template>

<script>
    export default {
        components: {
        },
        props: {
            corse: {},
            maxSize: {},
        },
        data() {
            return {
                isEnter : false,
                isDrop : false,
                file: null,
                preview: '',
                imageError: false,
                errorMessage: '',
                disabled: true,
                buttonClass: 'btn btn-secondary',
            };
        },
        methods: {
            dragEnter() {
                this.isEnter = true;
            },
            dragLeave() {
                this.isEnter = false;
            },
            imageValidation(img) {
                if (img.size > this.maxSize) {
                    this.imageError = true;
                    this.errorMessage = 'アップロード出来る画像サイズは 2MB 以下です';
                    return false;
                }
                if (img.type !== 'image/png') {
                    this.imageError = true;
                    this.errorMessage = '画像形式は .png でアップロードして下さい';
                    return false;
                }
                return true;
            },
            // 通常、ファイルをドロップしたときにはブラウザがドロップされた画像を表示するような動きをするが、
            // @drop.preventとすることでその機能を止めている。
            dropFile(event) {
                const files = event.dataTransfer.files;
                this.file = files[0];
                this.isEnter = false;
                this.isDrop = true;
                if (!this.imageValidation(this.file)) {
                    this.disabled = true;
                    this.preview = '/images/upload_file_error.png';
                    this.buttonClass = 'btn btn-secondary';
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.preview = e.target.result
                }
                reader.readAsDataURL(this.file);
                document.getElementById("upload_image").files = files;
                this.imageError = false;
                this.errorMessage = '';
                this.disabled = false;
                this.buttonClass = 'btn btn-success';
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