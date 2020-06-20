<template>
    <section v-if="isLoading">
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center" style="height:20em;">
                <div class="spinner-border text-secondary align-self-center spinner-border-lg" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </section>
    <div v-else class="content">
        <div class="row">
            <div class="col-md-6 offset-md-3 h100 content">
                <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <button type="button" class="btn btn-primary btn-block" @click="sendEmail()">Submit</button>
            </div>
        </div>
    </div>
</template>
<style>
    .ck-editor__editable {
        min-height: 400px;
    }
</style>
<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        data() {
            return {
                isLoading: false,
                editor: ClassicEditor,
                editorData: null,
                plugins: [ClassicEditor.ImageResize],
                editorConfig: {
                    cloudServices: {
                        tokenUrl: 'https://72421.cke-cs.com/token/dev/4VPVyEVL8ojNKJT16BkEpPHwttJmhghQ9HqMJlGigWpxuyftGp2ArpHePQ6Y',
                        uploadUrl: 'https://72421.cke-cs.com/easyimage/upload/'
                    },
                },
            };
        },
        methods:{
            sendEmail(){
                if(this.editorData == null){
                    this.$buefy.snackbar.open({
                        duration: 5000,
                        message: 'Isi Pesan Terlebih Dahulu Untuk Melakukan<br>Broadcast Notifikasi',
                        type: 'is-danger',
                        position: 'is-top-right',
                        queue: false,
                        onAction: () => {
                            this.$buefy.toast.open({
                                message: 'Action pressed',
                                queue: false
                            })
                        }
                    })
                }else{
                    this.isLoading = true;
                    axios.post('http://manawa.akugap.tech/api/crm/notification/post', {
                        message: this.editorData
                    }).then(response => {
                        this.$buefy.toast.open({message: `Submit Success`, position: 'is-bottom'})
                        console.log("sukses");
                        this.isLoading = false;
                        this.editorData = '';
                    })
                }
            }
        }
    }
</script>
