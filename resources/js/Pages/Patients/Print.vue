<template>
    <Head>
        <title>{{patient.name}}</title>
    </Head>

    <Link :href="route('patients.show', patient.id)" class="back-to-show">Вернуться назад</Link>

    <div class="container">

        <div class="row mb-5 page-title">
            <div class="col-6 text-center">
                Городской клинический центр <br> кожных болезней
            </div>
            <div class="col-6 text-center">
                Маркази клиникавии шахрии <br> беморихои пуст
            </div>
        </div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td width="300">ФИО пациента</td>
                <td>{{ patient.name }}</td>
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
            <tr>
                <td>Номер кейса</td>
                <td>
                    <div v-for="case_number in patient.case_numbers">{{ case_number }}</div>
                </td>
            </tr>
            </tbody>
        </table>

        <h4 class="mt-5 mb-3">Гистопатологическое заключение</h4>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <td width="300">Тип/место забора образца</td>
                <td>
                    {{ patient.categories }}
                </td>
            </tr>
            <tr>
                <td>Микроскопическое описание</td>
                <td>
                    <span v-if="patient.status === 2" v-html="patient.microscopic_description" class="editor-content"/>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Диагноз</b>
                </td>
                <td>
                    <span v-if="patient.status === 2" v-html="patient.diagnosis" class="editor-content"/>
                </td>
            </tr>
            <tr>
                <td>Заметка</td>
                <td>
                    <span v-if="patient.status === 2" v-html="patient.note" class="editor-content"/>
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

        <table class="sign-block mt-5 mb-5" width="100%">
            <tr>
                <td width="50%" class="text-right">Врач дерматопатолог:</td>
                <td width="15%"></td>
                <td width="50%">Султонов Р. А.</td>
            </tr>
            <tr>
                <td class="text-right">Дата:</td>
                <td></td>
                <td>
                    <input v-if="isDateEdit && $page.props.shared.userPermissions.includes('add_report')"
                           v-maska data-maska="##.##.####"
                           placeholder="формат даты: ДД.ММ.ГГГГ"
                           v-model="form.print_date"
                           @keydown.enter="savePrintDate"
                           @blur="savePrintDate"
                           type="text" class="form-control form-control-sm"/>

                    <span v-else>
                        {{ patient.print_date || patient.created_at }}
                        <small class="btn btn-link btn-sm edit-btn" @click="isDateEdit = !isDateEdit">ред.</small>
                    </span>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import {vMaska} from "maska"

export default {
    components: {Head, Link},
    props: ['patient', 'currentDate', 'qrCode'],
    layout: AuthLayout,
    directives: {maska: vMaska},
    data: function () {
        return {
            isDateEdit: false,
            form: useForm({
                print_date: this.patient.print_date
            })
        }
    },
    methods: {
        savePrintDate() {
            this.form.post(route('patients.edit_print_date', this.patient.id), {preserveState: true, preserveScroll: true});

            this.isDateEdit = false;
        }
    }
}
</script>

<style scoped>
@page {
    size: auto;
    margin: 0 50px;
}

.page-title {
    font-size: 20px;
    font-weight: 500;
}

.container {
    margin-top: 90px;
}

.back-to-show {
    margin: 30px;
    display: block;
}

.sign-block {
    font-size: 20px;
}

@media print {
    .back-to-show {
        display: none;
    }

    .edit-btn {
        display: none;
    }
}
</style>
