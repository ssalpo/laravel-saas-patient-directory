<template>
    <Head>
        <title>{{patient?.id ? 'Обновление данных пациента' : 'Новый пациент'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ patient?.id ? 'Обновление данных пациента' : 'Новый пациент' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <div class="card-body">
                    <form-input
                        label="Ф.И.О"
                        required
                        v-model.trim="form.name"
                        :validation-error="errors.name"
                    />

                    <form-input
                        label="Место проживания"
                        v-model.trim="form.place_of_residence"
                        :validation-error="errors.place_of_residence"
                    />

                    <form-input
                        label="Номер медицинской записи"
                        v-model.trim="form.medical_card_number"
                        :validation-error="errors.medical_card_number"
                    />

                    <form-input
                        label="Номер телефона"
                        required
                        v-maska data-maska="+############"
                        placeholder="пример: +992929927233"
                        v-model.trim="form.phone"
                        :validation-error="errors.phone"
                    />

                    <form-input
                        label="Дата рождения"
                        required
                        v-maska data-maska="##.##.####"
                        placeholder="ДД.ММ.ГГГГ"
                        v-model.trim="form.birthday"
                        :validation-error="errors.birthday"
                    />

                    <div class="form-group">
                        <label>Пол</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       :class="{'is-invalid': errors.gender}"
                                       type="radio" v-model="form.gender" name="gender"
                                       :value="1">
                                <label class="form-check-label">Мужской</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       :class="{'is-invalid': errors.gender}"
                                       type="radio" v-model="form.gender" name="gender"
                                       :value="0">
                                <label class="form-check-label">Женский</label>
                            </div>
                        </div>

                        <div v-if="errors.gender" class="error invalid-feedback">
                            {{ errors.gender }}
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <form-save-button
                        type="button"
                        @click="patient?.id ? submit() : null"
                        :data-toggle="!patient?.id ? 'modal' : ''"
                        :data-target="!patient?.id ? '#confirm-modal' : ''" :disabled="form.processing"
                        :is-processing="form.processing"
                        :is-editing="patient?.id"
                    />

                    <form-cancel-button
                        :url="route('patients.index')"
                        :is-processing="form.processing"
                    />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Проверьте корректность данных</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <td width="270">Ф.И.О</td>
                            <td>{{ form.name }}</td>
                        </tr>
                        <tr>
                            <td>Дата рождения</td>
                            <td>{{ form.birthday }}</td>
                        </tr>
                        <tr>
                            <td>Место проживания</td>
                            <td>{{ form.place_of_residence }}</td>
                        </tr>
                        <tr>
                            <td>Пол</td>
                            <td>{{ form.gender ? 'Мужской' : 'Женский' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Изменить данные</button>

                    <button type="button"
                            @click="submit"
                            class="btn btn-primary"
                            :disabled="form.processing">
                        <span v-if="form.processing">
                            <i class="fas fa-spinner fa-spin"></i> Сохранение...
                        </span>

                        <span v-else>{{ patient?.id ? 'Сохранить' : 'Добавить' }}</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/vue3";
import {vMaska} from "maska"
import resizeImage from "../../utils/resizeImage";
import FormInput from "../../Shared/Form/FormInput.vue";
import FormTextarea from "../../Shared/Form/FormTextarea.vue";
import FormCancelButton from "../../Shared/Form/FormCancelButton.vue";
import FormSaveButton from "../../Shared/Form/FormSaveButton.vue";
import FormSelectRoles from "../../Shared/Form/FormSelectRoles.vue";

export default {
    props: ['patient', 'errors'],
    components: {
        FormSelectRoles,
        FormSaveButton,
        FormCancelButton,
        FormTextarea,
        FormInput,
        Head,
        Link
    },
    directives: {maska: vMaska},
    data() {
        return {
            form: useForm({
                name: this.patient?.name,
                medical_card_number: this.patient?.medical_card_number,
                place_of_residence: this.patient?.place_of_residence,
                phone: this.patient?.phone,
                birthday: this.patient?.birthday || '',
                gender: this.patient?.gender !== undefined ? this.patient?.gender : 1,
            }),
        }
    },
    methods: {
        submit() {
            if (!this.patient?.id) {
                $('#confirm-modal').modal('hide');
                this.form.post('/patients', {forceFormData: true});
                return;
            }

            this.form
                .transform(data => {
                    data['_method'] = 'put';
                    return data;
                })
                .post(`/patients/${this.patient.id}`, {forceFormData: true})
        }
    }
}
</script>
