<style>
    .dropzone-field .dropzone {
        min-height: 300px; 
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<template>
    <div class="contents">
        <div class="col-12 mb-3">
            <h5 class="pb-1 border-rgba(0, 0, 0, 0.125) border-bottom h5">コース名：{{ corse.name }}</h5>
        </div>
        <div class="col-md-6">
            <div class="dropzone-field">
                <vue-dropzone id="score_upload" :options="dropzoneOptions"></vue-dropzone>
            </div>
            <div class="preview-item">
                <img
                    v-show="uploadedImage"
                    class="preview-item-file"
                    :src="uploadedImage"
                    alt=""
                    style="max-width: 100%; max-height: 100%;"
                >
                <div v-show="uploadedImage" class="preview-item-btn" @click="remove">
                    <p class="preview-item-name">{{ img_name }}<span style="cursor: pointer;"><i class="fas fa-times ml-2"></i></span></p>
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
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        props: {
            corse: {},
        },
        data() {
            return {
                uploadedImage: '',
                img_name: '',
                dropzoneOptions: {
                    url: function() {
                        return document.getElementById('upload-form').action;
                    },
                    maxThumbnailFilesize: 5,
                    maxFilesize: 5,
                    dictDefaultMessage:'ここにスコアカード画像を<br>ドロップするかクリックしてください',
                    sending: function(file, xhr, formData) {
                        let token = document.head.querySelector('meta[name="csrf-token"]').content;
                        formData.append("_token", token);
                    },
                },
            };
        },
        methods: {
            onFileChange(e) {
                const files = e.target.files || e.dataTransfer.files;
                this.createImage(files[0]);
                this.img_name = files[0].name;
            },
            // アップロードした画像を表示
            createImage(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.uploadedImage = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            remove() {
                this.uploadedImage = false;
            },
        },
    };
</script>