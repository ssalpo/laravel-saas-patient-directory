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
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-asterisk">Ф.И.О</label>
                        <input type="text" class="form-control"
                               :class="{'is-invalid': errors.name}"
                               v-model="form.name">

                        <div v-if="errors.name" class="error invalid-feedback">
                            {{ errors.name }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Номер телефона</label>
                        <input type="text" class="form-control"
                               :class="{'is-invalid': errors.phone}"
                               v-maska data-maska="+############"
                               placeholder="пример: +992 (92) 992-72-33"
                               v-model="form.phone">

                        <div v-if="errors.phone" class="error invalid-feedback">
                            {{ errors.phone }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-asterisk">Дата рождения</label>

                        <input :class="{'is-invalid': errors.birthday}"
                               class="form-control"
                               v-maska data-maska="##.##.####"
                               placeholder="ДД.ММ.ГГГГ"
                               v-model="form.birthday" />

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
                        <label class="form-asterisk">Дата/время забора образца</label>

                        <input :class="{'is-invalid': errors.sampling_date}"
                               class="form-control"
                               v-maska data-maska="##.##.#### ##:##"
                               placeholder="ДД.ММ.ГГГГ ЧЧ:ММ"
                               v-model="form.sampling_date" />

                        <div v-if="errors.sampling_date" class="error invalid-feedback">
                            {{ errors.sampling_date }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-asterisk">Дата/время получения образца</label>

                        <input :class="{'is-invalid': errors.sample_receipt_date}"
                               class="form-control"
                               v-maska data-maska="##.##.#### ##:##"
                               placeholder="ДД.ММ.ГГГГ ЧЧ:ММ"
                               v-model="form.sample_receipt_date" />

                        <div v-if="errors.sample_receipt_date" class="error invalid-feedback">
                            {{ errors.sample_receipt_date }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Анамнез</label>

                        <textarea :class="{'is-invalid': errors.anamnes}"
                                  class="form-control"
                                  v-model="form.anamnes"
                        ></textarea>

                        <div v-if="errors.anamnes" class="error invalid-feedback">
                            {{ errors.anamnes }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-asterisk">Тип/Место забора образца</label>

                        <div v-for="(category, index) in form.categories">
                            <div class="row mt-2">
                                <div class="col-3 col-sm-2 pb-2 pb-sm-0">
                                    <input type="text" v-model="category.code" class="form-control form-control-sm"
                                           placeholder="Введите код, например A1 или B1">
                                </div>
                                <div class="col-9 col-sm-4 pb-2 pb-sm-0">
                                    <div class="row" v-if="!category?.biopsyCustom || category?.biopsyCustom == 0" >
                                        <div class="col-12">
                                            <select @change="biopsyCustomToggle(category, $event.target.value === 'свой вариант')" class="form-control form-control-sm" v-model="category.biopsy">
                                                <option value="" disabled>Выберите тип биопсии</option>
                                                <option :value="biopsy" v-for="biopsy in biopsyTypes">{{biopsy}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" v-else>
                                        <div class="col-10">
                                            <input type="text" class="form-control form-control-sm" v-model="category.biopsyCustomValue">
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-sm" @click="biopsyCustomToggle(category, false)">
                                                <span class="fa fa-times"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-10 col-sm-4">
                                    <input type="text" v-model="category.description"
                                           class="form-control form-control-sm"
                                           placeholder="Введите описание, например рука">
                                </div>
                                <div class="col-2">
                                    <button type="button" @click="removeCategory(index)" class="btn btn-sm btn-warning">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>


                            <div class="row mt-2" v-if="errors[`categories.${index}.code`] || errors[`categories.${index}.description`] || errors[`categories.${index}.biopsy`] || errors[`categories.${index}.biopsyCustomValue`] || errors[`categories.${index}.biopsyCustom`]">
                                <div class="col-3 col-sm-2 pb-2 pb-sm-0">
                                    <div v-if="errors[`categories.${index}.code`]" class="invalid-feedback-simple">
                                        {{errors[`categories.${index}.code`]}}
                                    </div>
                                </div>
                                <div class="col-9 col-sm-4 pb-2 pb-sm-0">
                                    <div v-if="errors[`categories.${index}.biopsy`] || errors[`categories.${index}.biopsyCustomValue`] || errors[`categories.${index}.biopsyCustom`]" class="invalid-feedback-simple">
                                        {{errors[`categories.${index}.biopsy`] || errors[`categories.${index}.biopsyCustomValue`] || errors[`categories.${index}.biopsyCustom`]}}
                                    </div>
                                </div>
                                <div class="col-10 col-sm-4">
                                    <div v-if="errors[`categories.${index}.description`]" class="invalid-feedback-simple">
                                        {{errors[`categories.${index}.description`]}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="button" @click="addCategory" class="btn btn-sm btn-info mt-2">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group"
                         v-if="$page.props.shared.userPermissions.includes('select_doctor_patients')">
                        <label class="form-asterisk">Направивший врач</label>

                        <div v-if="!newDoctor">
                            <select class="form-control" :class="{'is-invalid': errors.doctor}"
                                    v-model="form.doctor">
                                <option :value="null">Не выбран</option>
                                <option v-for="doctor in doctors" :value="doctor.id">{{ doctor.name }}</option>
                            </select>

                            <button @click="toggleNewDoctor" type="button" class="btn btn-sm btn-link">
                                + Новый врач
                            </button>
                        </div>

                        <div v-else>
                            <input type="text"
                                   v-model.trim="form.doctor"
                                   class="form-control form-control-sm"
                                   placeholder="Введите имя доктора">

                            <input type="text"
                                   placeholder="Номер телефона (необязательно), пример: +992 (92) 992-72-33"
                                   class="form-control form-control-sm mt-2"
                                   v-maska data-maska="+############"
                                   v-model="form.doctor_phone">

                            <button @click="toggleNewDoctor" type="button" class="btn btn-sm btn-link">
                                Выбрать из списка
                            </button>
                        </div>

                        <div v-if="errors.doctor" class="invalid-feedback-simple">
                            {{ errors.doctor }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Фото</label>

                        <div class="custom-file">
                            <input type="file" :class="{'is-invalid': errors.photos}"
                                   multiple
                                   @input="resizeImages($event.target.files)"
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
                    <button type="button" @click="id ? submit() : null" :data-toggle="!id ? 'modal' : ''"
                            :data-target="!id ? '#confirm-modal' : ''" :disabled="form.processing"
                            class="btn btn-primary">
                        <span v-if="form.processing">
                            <i class="fas fa-spinner fa-spin"></i> Сохранение...
                        </span>

                        <span v-else>{{ id ? 'Сохранить' : 'Добавить' }}</span>
                    </button>

                    <Link :href="route('patients.index')" :class="{disabled: form.processing}"
                          class="btn btn-default ml-2">Отменить
                    </Link>
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
                            <td>Пол</td>
                            <td>{{ form.gender ? 'Мужской' : 'Женский' }}</td>
                        </tr>
                        <tr>
                            <td>Дата/время забора образца</td>
                            <td>{{ form.sampling_date }}</td>
                        </tr>
                        <tr>
                            <td>Дата/время получения образца</td>
                            <td>{{ form.sample_receipt_date }}</td>
                        </tr>
                        <tr>
                            <td>Анамнез</td>
                            <td>{{ form.anamnes }}</td>
                        </tr>
                        <tr>
                            <td>Тип/Место забора образца</td>
                            <td>
                                <div v-for="category in form.categories">{{ category.code }}
                                    ({{category.biopsy ? (category?.biopsyCustom ? category.biopsyCustomValue : category.biopsy) + ',' : ''}} {{ category.description }})
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Направивший врач</td>
                            <td>{{ selectedDoctor }}</td>
                        </tr>
                        <tr>
                            <td>Количество прикрепленных фото</td>
                            <td>{{ form.photos.length }}</td>
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

                        <span v-else>{{ id ? 'Сохранить' : 'Добавить' }}</span>
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
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import { vMaska } from "maska"
import resizeImage from "../../utils/resizeImage";

export default {
    props: ['id', 'doctors', 'patient', 'errors'],
    components: {Head, Link},
    directives: { maska: vMaska },
    data() {
        return {
            customBiopsyToggle: [],
            biopsyTypes: ['шейв-биопсия', 'панч-биопсия', 'свой вариант'],
            newDoctor: false,
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
                phone: this.patient?.phone,
                birthday: this.patient?.birthday || '',
                gender: this.patient?.gender !== undefined ? this.patient.gender : 1,
                sampling_date: this.patient?.sampling_date,
                sample_receipt_date: this.patient?.sample_receipt_date,
                anamnes: this.patient?.anamnes,
                doctor: this.patient?.doctor || null,
                doctor_phone: this.patient?.doctor_phone || null,
                categories: this.patient?.categories || [{code: 'A1', biopsy: 'шейв-биопсия', biopsyCustomValue: null, biopsyCustom: false, description: ''}],
                photos: this.patient?.photos || [],
            }),
        }
    },
    computed: {
        selectedDoctor() {
            return this.doctors[parseFloat(this.form.doctor)] || this.form.doctor
        }
    },
    methods: {
        submit() {
            if (!this.id) {
                $('#confirm-modal').modal('hide');
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
            this.form.categories.push({code: '', biopsy: null, biopsyCustom: false, biopsyCustomValue: null, description: ''})
        },
        biopsyCustomToggle(category, isCustom) {
            category.biopsyCustom = isCustom

            if(isCustom) {
                category.biopcy = null
            } else {
                category.biopsyCustomValue = null
            }
        },
        removeCategory(index) {
            this.form.categories.splice(index, 1);
        },
        toggleNewDoctor() {
            this.newDoctor = !this.newDoctor;
            this.form.doctor = null
            this.form.doctor_phone = null
        },
        resizeImages: async function (values) {
            let resizedImages = [];

            for (let i = 0; i < values.length; i++) {

                const config = {
                    file: values[i],
                    maxSize: 1800
                };

                resizedImages.push(await resizeImage(config))
            }

            this.form.photos = resizedImages;
        }
    }
}
</script>
