<template>
    <Head>
        <title>{{patient.data.name}}</title>
    </Head>

    <Link :href="route('patients.show', patient.data.id)" class="back-to-show">Вернуться назад</Link>

    <div class="container">

        <div class="row mb-5 page-title">
            <div class="col-6 text-center">
                ГУ «Городская клиническая больница <br /> кожных болезней»
            </div>
            <div class="col-6 text-center">
                МД «Беморхонаи клиникавии шахрии <br /> беморихои пуст»
            </div>
        </div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td width="300">ФИО пациента</td>
                <td>{{ patient.data.name }}</td>
            </tr>
            <tr>
                <td width="300">Место проживания</td>
                <td>{{ patient.data.place_of_residence }}</td>
            </tr>
            <tr>
                <td>Дата рождения</td>
                <td>{{ patient.data.birthday }}</td>
            </tr>
            <tr>
                <td>Пол</td>
                <td>{{ patient.data.gender ? 'М' : 'Ж' }}</td>
            </tr>
            <tr>
                <td>Номер медицинской записи</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Дата/время забора образца</td>
                <td>{{ patient.data.sampling_date }}</td>
            </tr>
            <tr>
                <td>Дата/время получения образца</td>
                <td>{{ patient.data.sample_receipt_date }}</td>
            </tr>
            <tr>
                <td>Номер кейса</td>
                <td>
                    <div v-for="case_number in patient.data.case_numbers">{{ case_number }}</div>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="mt-5 mb-3 page-title-second">Гистопатологическое заключение</div>

        <table class="table table-bordered">
            <tbody>
            <tr>
                <td width="300">Тип/место забора образца</td>
                <td>
                    {{ patient.data.categories_formatted }}
                </td>
            </tr>
            <tr>
                <td>Микроскопическое описание</td>
                <td>
                    <span v-if="patient.data.status === 2" v-html="patient.data.microscopic_description" class="editor-content"/>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Диагноз</b>
                </td>
                <td>
                    <span v-if="patient.data.status === 2" v-html="patient.data.diagnosis" class="editor-content"/>
                </td>
            </tr>
            <tr :class="{'hide-from-print': movedNoteToNewPage}">
                <td>
                    Заметка

                    <div>
                        <a href="#" class="hide-from-print" @click.prevent="movedNoteToNewPage = !movedNoteToNewPage">
                            {{movedNoteToNewPage ? 'Перенесено' : 'Перенести'}} на новую страницу
                        </a>
                    </div>
                </td>
                <td>
                    <span v-if="patient.data.status === 2" v-html="patient.data.note" class="editor-content"/>
                </td>
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
                        {{ patient.data.print_date || currentDate }}
                        <small class="btn btn-link btn-sm edit-btn" @click="isDateEdit = !isDateEdit">ред.</small>
                    </span>
                </td>
            </tr>
        </table>

        <div class="only-int-print" v-if="movedNoteToNewPage">
            <div class="pagebreak"></div>

            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td width="300">Заметка</td>
                    <td v-html="patient.data.note"></td>
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
                        {{ patient.data.print_date || patient.data.created_at }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import {Head, Link, useForm} from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import {vMaska} from "maska"

export default {
    components: {Head, Link},
    props: ['patient', 'currentDate'],
    layout: AuthLayout,
    directives: {maska: vMaska},
    data: function () {
        return {
            movedNoteToNewPage: false,
            isDateEdit: false,
            form: useForm({
                print_date: this.patient.data.print_date
            })
        }
    },
    methods: {
        savePrintDate() {
            this.form.post(route('patients.edit_print_date', this.patient.data.id), {
                preserveState: true,
                preserveScroll: true
            });

            this.isDateEdit = false;
        }
    }
}
</script>

<style scoped>
@page {
    size: A4;
}

.only-int-print {
    display: none;
}

.page-title {
    font-size: 20px;
    font-weight: 600;
}
.page-title-second {
    font-size: 22px;
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
    .hide-from-print {
        display: none;
    }

    .back-to-show {
        display: none;
    }

    .page-title {
        font-size: 18pt;
    }

    .page-title-second {
        font-size: 16pt;
        font-weight: 600;
    }

    table {
        font-size: 16pt;
    }

    .edit-btn {
        display: none;
    }

    .pagebreak {
        page-break-before: always;
        margin-top: 60px;
    }

    .container {
        margin-top: 0;
    }

    .only-int-print {
        display: block;
    }
}
</style>
