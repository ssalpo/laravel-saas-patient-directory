<script>
import {defineComponent} from 'vue'
import {useForm} from "@inertiajs/vue3";

export default defineComponent({
    name: "PatientPhotosModal",
    props: ['patient'],
    data: function () {
        return {
            originalPhotoShowed: false,
            photoLoading: false,
            photoLoadingError: false,
            selectedPhoto: '',
            selectedPhotoIndex: null,
            editBlock: '',
            form: useForm({
                note: this.patient.note || ''
            }),
            formComment: useForm({
                comment: this.patient?.comment || ''
            })
        }
    },
    computed: {
        selectedPhotoUrl() {
            return `/storage/${this.selectedPhoto.url}`
        }
    },
    created() {
        this.$Lazyload.$on('loading', (listener) => {
            this.photoLoading = true
        });

        this.$Lazyload.$on('loaded', (listener) => {
            this.photoLoading = false
        });

        this.$Lazyload.$on('error', (listener) => {
            this.photoLoading = false
            this.photoLoadingError = true
        });

        $(document).on('hide.bs.modal', '#photo-view-modal', () => {
            this.originalPhotoShowed = false
        });
    },
    methods: {
        deletePhoto(photo) {
            if (!confirm('Вы уверены что хотите удалить фотографию?')) return;

            this.form.delete(route('patients.photos.destroy', {patient: this.patient.id, photo}), {
                preserveState: true,
                preserveScroll: true
            })
        },
        showOriginalPhoto() {
            this.selectedPhoto = this.selectedPhoto.replace('/thumb', '');
            this.originalPhotoShowed = true;
        },
        selectPhoto(photo, index) {
            this.selectedPhoto = photo
            this.selectedPhotoIndex = index
        },
        slidePhoto(type) {
            if (type === 'next') {
                this.selectedPhotoIndex += 1;

                if (this.selectedPhotoIndex > this.patient.photos.length - 1) {
                    this.selectedPhotoIndex = 0;
                }
            }

            if (type === 'prev') {
                this.selectedPhotoIndex -= 1;

                if (this.selectedPhotoIndex < 0) {
                    this.selectedPhotoIndex = this.patient.photos.length - 1;
                }
            }

            this.selectedPhoto = this.patient.photos.find((_, i) => i === this.selectedPhotoIndex);
        }
    }
})
</script>

<template>
    <div v-for="(photo, index) in patient.photos"
         class="btn-group btn-group-sm d-inline-block mb-1" role="group">
        <button type="button"
                @click="selectPhoto(photo, index)"
                data-toggle="modal" data-target="#photo-view-modal"
                class="btn btn-default btn-sm mr-1">
            {{photo.label || `Фото ${index + 1}`}}
        </button>
        <button type="button"
                v-if="$page.props.shared.userId === patient.created_by"
                class="btn btn-danger btn-sm mr-1" @click="deletePhoto(photo.id)">
            <span class="fa fa-trash"></span>
        </button>
    </div>

    <div class="modal fade" id="photo-view-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    {{selectedPhoto.label || `Фото ${selectedPhotoIndex + 1}`}}, {{selectedPhoto.created_at}}

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">

                    <div class="row">
                        <div class="col-6 text-sm-left text-md-right">
                            <a :href="selectedPhotoUrl" target="_blank"
                               class="btn btn-sm btn-info mb-3 mr-3">
                                оригинал
                            </a>
                        </div>
                        <div class="col-6 text-sm-right text-md-left" v-if="patient.photos.length > 1">
                            <a href="#" @click.prevent="slidePhoto('prev')"
                               class="btn btn-default btn-sm mr-2">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            <a href="#" @click.prevent="slidePhoto('next')"
                               class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <span class="mt-2" v-if="photoLoading">Фотография загружается...</span>
                    <span class="mt-2" v-if="photoLoadingError">
                                        Ошибка загрузки фотографии, попробуйте еще раз.
                                    </span>

                    <div>
                        <img v-lazy="selectedPhotoUrl"
                             style="width: auto; max-width: 100%; max-height: 600px; display: block; margin: 0 auto;">
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</template>

<style scoped>

</style>
