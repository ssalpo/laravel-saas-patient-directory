<template>
    <Head>
        <title>Данные пациента</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Данные пациента</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 text-right">
                    <Link :href="route('patients.print', patient.id)" class="btn btn-warning mr-2">
                        <i class="fa fa-print"></i>
                    </Link>

                    <Link v-if="$page.props.shared.userPermissions.includes('edit_patients')"
                          :href="route('patients.edit', patient.id)" class="btn btn-primary">
                        <i class="fa fa-pencil-alt"></i>
                    </Link>
                </div><!-- /.col -->
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h4 class="mt-2 mb-3">Общие данные</h4>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td width="400">ФИО пациента</td>
                            <td>{{ patient.name }}</td>
                        </tr>
                        <tr>
                            <td width="400">Номер телефона</td>
                            <td>{{ patient.phone }}</td>
                        </tr>
                        <tr>
                            <td>Дата рождения</td>
                            <td>{{ patient.birthday }}</td>
                        </tr>
                        <tr>
                            <td>Пол</td>
                            <td>{{ patient.gender ? 'М' : 'Ж' }}</td>
                        </tr>
                        <tr>
                            <td>Номер медицинской записи</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Дата/время забора образца</td>
                            <td>{{ patient.sampling_date }}</td>
                        </tr>
                        <tr>
                            <td>Дата/время получения образца</td>
                            <td>{{ patient.sample_receipt_date }}</td>
                        </tr>
                        <tr :class="{'animate-background': $page.props.flash.isCreated === true}">
                            <td>Номер кейса</td>
                            <td>
                                <div v-for="case_number in patient.case_numbers">{{ case_number }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Направивший врач</td>
                            <td>
                                {{ patient.doctor }}
                            </td>
                        </tr>
                        <tr>
                            <td>Анамнез</td>
                            <td>
                                {{ patient.anamnes }}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <h4 class="mt-5 mb-3">Гистопатологическое заключение</h4>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td width="400">Тип/место забора образца</td>
                            <td>
                                {{ patient.categories }}
                            </td>
                        </tr>
                        <tr>
                            <td>Микроскопическое описание</td>
                            <td>

                                <div v-if="$page.props.shared.userPermissions.includes('add_report')">
                                    <QuillEditor theme="snow"
                                                 @blur="saveReport"
                                                 @ready="focusOnReady"
                                                 v-if="editBlock === 'microscopic_description' || !patient.microscopic_description"
                                                 contentType="html"
                                                 :toolbar="['bold', 'italic', 'underline']"
                                                 v-model:content="form.microscopic_description"/>

                                    <div v-else>
                                        <div class="editor-content" v-html="form.microscopic_description"></div>

                                        <a href="" @click.prevent="editBlock = 'microscopic_description'"><small>Редактировать</small></a>
                                    </div>
                                </div>
                                <div v-else>
                                    <span v-if="patient.status === 2">{{ patient.microscopic_description }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Диагноз</b>
                            </td>
                            <td>
                                <div v-if="$page.props.shared.userPermissions.includes('add_report')">
                                    <QuillEditor theme="snow"
                                                 @blur="saveReport"
                                                 @ready="focusOnReady"
                                                 v-if="editBlock === 'diagnosis' || !patient.diagnosis"
                                                 contentType="html"
                                                 :toolbar="['bold', 'italic', 'underline']"
                                                 v-model:content="form.diagnosis"/>

                                    <div v-else>
                                        <div class="editor-content" v-html="patient.diagnosis"></div>
                                        <a href="" @click.prevent="editBlock = 'diagnosis'"><small>Редактировать</small></a>
                                    </div>
                                </div>
                                <div v-else>
                                    <span v-if="patient.status === 2">{{ patient.diagnosis }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Заметка</td>
                            <td>
                                <div v-if="$page.props.shared.userPermissions.includes('add_report')">
                                    <QuillEditor theme="snow"
                                                 @blur="saveReport"
                                                 @ready="focusOnReady"
                                                 v-if="editBlock === 'note' || !patient.note"
                                                 contentType="html"
                                                 :toolbar="['bold', 'italic', 'underline']"
                                                 v-model:content="form.note"/>

                                    <div v-else>
                                        <div class="editor-content" v-html="patient.note"></div>
                                        <a href="" @click.prevent="editBlock = 'note'"><small>Редактировать</small></a>
                                    </div>
                                </div>
                                <div v-else>
                                    <span v-if="patient.status === 2">{{ patient.note }}</span>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="$page.props.shared.userPermissions.includes('add_report') && patient.status === 1">
                            <td>
                                <b>Статус проверки</b>
                            </td>
                            <td>
                                <Link :href="route('patients.submit', patient.id)" preserve-scroll
                                      class="btn btn-primary" method="post" as="button">Submit
                                </Link>
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="align-middle text-bold">Ссылка карточки пациента</td>
                            <td>
                                {{ route('patients.public_show', patient.hashid) }}
                            </td>
                        </tr>
                        <tr>
                            <td v-html="qrCode"/>
                        </tr>
                        </tbody>
                    </table>

                    <h4 class="mt-5 mb-3" v-show="patient.photos.length > 0">Прикрепленные фотография</h4>

                    <div v-for="(photo, index) in patient.photos"
                         class="btn-group btn-group-sm" role="group">
                        <button type="button"
                                @click="selectedPhoto = `/storage/${photo.url}`"
                                data-toggle="modal" data-target="#photo-view-modal"
                                class="btn btn-default btn-sm mr-1">Фото {{ index + 1 }}
                        </button>
                        <button type="button"
                                v-if="$page.props.shared.userPermissions.includes('edit_patients')"

                                class="btn btn-danger btn-sm mr-1" @click="deletePhoto(photo.id)">
                            <span class="fa fa-trash"></span>
                        </button>
                    </div>

                    <div class="modal fade" id="photo-view-modal">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <span v-if="photoLoading">Фотография загружается...</span>
                                    <span
                                        v-if="photoLoadingError">Ошибка загрузки фотографии, попробуйте еще раз.</span>

                                    <a :href="selectedPhoto" target="_blank" v-if="!photoLoading && !photoLoading"
                                       class="btn btn-sm btn-default mb-3 mr-3">
                                        открыть в новом окне
                                    </a>

<!--                                    <button v-if="!photoLoading && !photoLoading && !originalPhotoShowed"
                                            @click="showOriginalPhoto" class="btn btn-sm btn-primary mb-3">
                                        показать оригинал
                                    </button>-->

                                    <img v-lazy="selectedPhoto" style="max-width: 100%; height: 100%">
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import {QuillEditor} from '@vueup/vue-quill';

export default {
    components: {Head, Link, QuillEditor},
    props: ['patient', 'qrCode'],
    data: function () {
        return {
            originalPhotoShowed: false,
            photoLoading: false,
            photoLoadingError: false,
            selectedPhoto: '',
            editBlock: '',
            someData: '',
            form: useForm({
                microscopic_description: this.patient?.microscopic_description || '',
                diagnosis: this.patient?.diagnosis || '',
                note: this.patient?.note || ''
            })
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
        saveReport() {
            this.form.post(route('patients.save.report', this.patient.id), {preserveState: true, preserveScroll: true})

            this.editBlock = ''
        },
        deletePhoto(photo) {
            if(!confirm('Вы уверены что хотите удалить фотографию?')) return;

            this.form.delete(route('patients.photos.delete', {patient: this.patient.id, photo}), {preserveState: true, preserveScroll: true})
        },
        showOriginalPhoto() {
            this.selectedPhoto = this.selectedPhoto.replace('/thumb', '');
            this.originalPhotoShowed = true;
        },
        focusOnReady(editor) {
            if (this.editBlock) editor.focus();
        }
    }
}
</script>
