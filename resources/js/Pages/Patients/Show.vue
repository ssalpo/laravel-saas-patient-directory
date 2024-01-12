<template>
    <Head>
        <title>Карточка пациента</title>
    </Head>

    <div class="content-header">
        <div class="row mb-2">
            <div class="col-sm-12 col-md-8">
                <h1 class="m-0">Карточка пациента</h1>
            </div><!-- /.col -->
            <div class="col-sm-12 col-md-4 text-sm-left pt-3 text-md-right pt-md-0" v-if="$page.props.shared.userId === patient.created_by">
                <PatientShareModal
                    :patient="patient"
                    @success="refreshPageData"
                    btn-class="btn btn-danger mr-2"
                />

                <Link :href="route('patients.edit', {patient: patient.id})"
                      class="btn btn-primary">
                    <i class="fa fa-pencil-alt"></i>
                </Link>
            </div><!-- /.col -->
        </div>
    </div>

    <div class="content">
        <div class="container">

            <div class="card card-primary d-block d-md-none">
                <div class="card-header">
                    Общие данные
                </div>
                <div class="card-body">
                    <div>
                        <strong>ФИО пациента</strong>
                        <p class="text-muted">
                            {{ patient.name }}
                        </p>
                        <hr>
                    </div>

                    <div>
                        <strong>Номер медицинской записи</strong>
                        <p class="text-muted">
                            {{ patient.medical_card_number }}
                        </p>
                        <hr>
                    </div>

                    <div>
                        <strong>Место проживания</strong>
                        <p class="text-muted">
                            {{ patient.place_of_residence }}
                        </p>
                        <hr>
                    </div>

                    <div>
                        <strong>Номер телефона</strong>
                        <p class="text-muted">
                            {{ patient.phone }}
                        </p>
                        <hr>
                    </div>

                    <div>
                        <strong>Дата рождения</strong>
                        <p class="text-muted">
                            {{ patient.birthday }}
                        </p>
                        <hr>
                    </div>

                    <div>
                        <strong>Пол</strong>
                        <p class="text-muted mb-0">
                            {{ patient.gender ? 'Мужской' : 'Женский' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card card-primary d-none d-md-block">
                <div class="card-header">
                    Общие данные
                </div>
                <div class="card-body">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td width="400" class="font-weight-bold">ФИО пациента</td>
                                <td>{{ patient.name }}</td>
                            </tr>
                            <tr>
                                <td width="400" class="font-weight-bold">Место проживания</td>
                                <td>{{ patient.place_of_residence }}</td>
                            </tr>
                            <tr>
                                <td width="400" class="font-weight-bold">Номер телефона</td>
                                <td>{{ patient.phone }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Дата рождения</td>
                                <td>{{ patient.birthday }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Пол</td>
                                <td>{{ patient.gender ? 'М' : 'Ж' }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Номер медицинской записи</td>
                                <td>{{ patient.medical_card_number }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Desktop -->
            <div class="card card-primary d-none d-md-block" v-for="block in blocks">
                <div class="card-header">
                    {{block.label}}
                </div>
                <div class="card-body">
                    <div class="table-responsive d-none d-md-block">
                        <table class="table table-bordered">
                            <tbody>
                            <tr v-for="child in block.childs">
                                <td width="400" class="font-weight-bold" v-if="child.label">{{ child.label }}</td>
                                <td>
                                    <PatientEditable
                                        :patient-id="patient.id"
                                        :value="patient[child.key]"
                                        :canEdit="$page.props.shared.userId === patient.created_by"
                                        :field="child.key"
                                    />
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Desktop -->

            <!-- Mobile -->
            <div class="card card-primary d-block d-md-none" v-for="block in blocks">
                <div class="card-header" v-show="block.label">
                    {{block.label}}
                </div>
                <div class="card-body">
                    <div v-for="child in block.childs">
                        <strong style="font-size: 20px; font-weight: 600; line-height: 18px;" v-if="child.label">{{child.label}}</strong>

                        <PatientEditable
                            :patient-id="patient.id"
                            :value="patient[child.key]"
                            :field="child.key"
                            :canEdit="$page.props.shared.userId === patient.created_by"
                        >
                            <template v-slot:text="{value}">
                                <p class="text-muted">
                                    {{value}}
                                </p>
                            </template>
                        </PatientEditable>
                        <hr>
                    </div>
                </div>
            </div>
            <!-- End Mobile -->

            <div class="card card-primary" v-if="$page.props.shared.userId === patient.created_by || patient.photos.length">
                <div class="card-header">
                    Прикрепленные фотография
                </div>
                <div class="card-body">
                    <PatientPhotosModal :patient="patient"/>

                    <hr v-show="patient.photos.length" />

                    <PatientPhotoUpload :errors="errors" :patient-id="patient.id"/>
                </div>
            </div>

            <h2 class="mt-4 mb-3" v-show="consultations.length">Консультации</h2>

            <SendPatientConsultation
                v-if="patient.share_to_user_id === $page.props.shared.userId"
                :errors="errors"
                :patient-id="patient.id"
                class="mb-4"
            />

            <div class="card card-outline card-success" v-for="consultation in consultations">
                <div class="card-header">
                    <div class="user-block">
                        <span class="username ml-0">
                            <a href="javascript:void(0)">{{ consultation.user.name }}</a>
                        </span>
                        <span class="description ml-0">Дата добавления: {{ consultation.created_at }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div v-html="consultation.content"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, router} from "@inertiajs/vue3";
import {QuillEditor} from '@vueup/vue-quill';
import PatientShareModal from "../../Shared/Modals/PatientShareModal.vue";
import SendPatientConsultation from "../../Shared/Form/SendPatientConsultation.vue";
import PatientPhotoUpload from "../../Shared/Form/PatientPhotoUpload.vue";
import PatientPhotosModal from "../../Shared/Modals/PatientPhotosModal.vue";
import PatientEditable from "../../Shared/PatientEditable.vue";

export default {
    components: {
        PatientEditable,
        PatientPhotosModal,
        PatientPhotoUpload, SendPatientConsultation, PatientShareModal, Head, Link, QuillEditor
    },
    props: ['patient', 'qrCode', 'errors'],
    data: function () {
        return {
            blocks: [
                {
                    label: 'Анамнез',
                    childs: [
                        {label: 'Жалобы и история настоящего заболевания', key: 'morbi'},
                        {label: 'История перенесённых заболеваний и хирургическая история', key: 'vitae'},
                        {label: 'Данные дополнительных методов исследования, заключения консультантов', key: 'lab_workup'},
                    ]
                },
                {
                    label: 'Диагноз',
                    childs: [
                        {label: 'Диагноз основного заболевания', key: 'diagnosis'},
                        {label: 'Код по МКБ10', key: 'mkb'},
                        {label: 'Лечение и проведённые процедуры', key: 'treatment'},
                        {label: 'Комментарий', key: 'comment'},
                    ]
                },
                {
                    label: 'Лечение',
                    childs: [
                        {label: 'Лечение и проведённые процедуры', key: 'treatment'},
                    ]
                },
                {
                    label: 'Комментарий',
                    childs: [
                        {label: false, key: 'comment'},
                    ]
                },
            ]
        }
    },
    computed: {
        consultations() {
            return this.patient?.currentUserConsultations || this.patient?.consultations || []
        }
    },
    methods: {
        refreshPageData() {
            router.reload({
                only: ['patient']
            })
        }
    }
}
</script>
