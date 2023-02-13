<template>
    <Head>
        <title>{{id ? 'Обновление данных пациента' : 'Новый пациент'}}</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">{{ id ? 'Обновление данных пациента' : 'Новый пациент' }}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card card-primary">
                <!-- form start -->
                <form @submit.prevent="submit">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Ф.И.О</label>
                            <input type="text" class="form-control"
                                   :class="{'is-invalid': errors.name}"
                                   v-model="form.name">

                            <div v-if="errors.name" class="error invalid-feedback">
                                {{ errors.name }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Дата рождения</label>

                            <date-time-picker :class="{'is-invalid': errors.birthday}" v-model="form.birthday"
                                              :value="form.birthday" :config="birthdayConfig"/>

                            <div v-if="errors.birthday" class="error invalid-feedback">
                                {{ errors.birthday }}
                            </div>
                        </div>
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
                        <div class="form-group">
                            <label>Дата/время забора образца</label>

                            <date-time-picker :class="{'is-invalid': errors.sampling_date}" v-model="form.sampling_date"
                                              :value="form.sampling_date" :config="sampleConfig"/>

                            <div v-if="errors.sampling_date" class="error invalid-feedback">
                                {{ errors.sampling_date }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Дата/время получения образца</label>

                            <date-time-picker :class="{'is-invalid': errors.sample_receipt_date}"
                                              v-model="form.sample_receipt_date" :value="form.sample_receipt_date"
                                              :config="sampleConfig"/>

                            <div v-if="errors.sample_receipt_date" class="error invalid-feedback">
                                {{ errors.sample_receipt_date }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Анамнез</label>
                            <input type="text" :class="{'is-invalid': errors.anamnes}" class="form-control"
                                   v-model="form.anamnes">

                            <div v-if="errors.anamnes" class="error invalid-feedback">
                                {{ errors.anamnes }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Тип/Место забора образца</label>
                            <div class="row mt-2" v-for="(category, index) in form.categories">
                                <div class="col-4">
                                    <input type="text" v-model="category.code" class="form-control form-control-sm"
                                           placeholder="Введите код, например A1 или B1">
                                </div>
                                <div class="col-4">
                                    <input type="text" v-model="category.description"
                                           class="form-control form-control-sm"
                                           placeholder="Введите описание, например рука">
                                </div>
                                <div class="col-4">
                                    <button type="button" @click="removeCategory(index)" class="btn btn-sm btn-warning">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <button type="button" @click="addCategory" class="btn btn-sm btn-info mt-2">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Направивший врач</label>

                            <select class="form-control" :class="{'is-invalid': errors.doctor_id}"
                                    v-model="form.doctor_id">
                                <option :value="null">Не выбран</option>
                                <option v-for="doctor in doctors" :value="doctor.id">{{ doctor.name }}</option>
                            </select>

                            <div v-if="errors.doctor_id" class="error invalid-feedback">
                                {{ errors.doctor_id }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Фото</label>

                            <div class="custom-file">
                                <input type="file" :class="{'is-invalid': errors.photos}"
                                       multiple
                                       @input="form.photos = $event.target.files"
                                       class="custom-file-input">
                                <label class="custom-file-label">
                                    {{
                                        form.photos.length ? `Выбранных файлов ${form.photos.length}` : 'Выбрать файлы'
                                    }}
                                </label>
                            </div>

                            <div v-if="errors.photos" class="error invalid-feedback">
                                {{ errors.photos }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" :disabled="form.processing" class="btn btn-primary">
                            {{ id ? 'Сохранить' : 'Добавить' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import DateTimePicker from "../../Shared/DateTimePicker.vue";

export default {
    props: ['id', 'doctors', 'patient', 'errors'],
    components: {DateTimePicker, Head, Link},
    data() {
        return {
            birthdayConfig: {
                locale: 'ru',
                format: 'DD.MM.YYYY'
            },
            sampleConfig: {
                locale: 'ru',
                icons: {time: 'far fa-clock'},
                format: 'DD.MM.YYYY HH:mm'
            },
            form: useForm({
                name: this.patient?.name,
                birthday: this.patient?.birthday || '',
                gender: this.patient?.gender !== undefined ? this.patient.gender : 1,
                sampling_date: this.patient?.sampling_date,
                sample_receipt_date: this.patient?.sample_receipt_date,
                anamnes: this.patient?.anamnes,
                doctor_id: this.patient?.doctor_id,
                categories: this.patient?.categories || [],
                photos: this.patient?.photos || [],
            }),
        }
    },
    methods: {
        submit() {
            if (!this.id) {
                this.form.post('/patients', {forceFormData: true});
                return;
            }

            this.form
                .transform(data => {
                    data['_method'] = 'put';
                    return data;
                })
                .post(`/patients/${this.id}`, {forceFormData: true})
        },
        addCategory() {
            this.form.categories.push({code: '', description: ''})
        },
        removeCategory(index) {
            this.form.categories.splice(index, 1);
        }
    }
}
</script>
