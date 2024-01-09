<template>
    <Head>
        <title>Дерматопатология</title>
    </Head>

    <div class="content">
        <div class="container-fluid p-sm-1 p-md-5">
            <Link :href="route('patients.index')" class="mt-2 mt-sm-0 mb-3 d-block">Назад к списку</Link>

            <div class="table-responsive">
                <table class="table table-hover table-bordered" style="width: 3000px">
                    <thead>
                    <tr>
                        <th class="align-middle" width="500">Ф.И.О</th>
                        <th class="align-middle" width="500">Место проживания</th>
                        <th class="align-middle" width="500">Заметка</th>
                        <th class="align-middle" width="500">Комментарий</th>
                        <th class="align-middle" width="400">Номер телефона</th>
                        <th class="align-middle" width="400">Дата рождения</th>
                        <th class="align-middle">Возраст</th>
                        <th class="align-middle">Пол</th>
                        <th class="align-middle" v-for="block in blocks" :width="block.headerWidth">{{block.label}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="patient in patients">
                        <td>{{ patient.name }}</td>
                        <td>{{ patient.place_of_residence }}</td>
                        <td class="diagnosis-table" v-html="patient.note"></td>
                        <td class="diagnosis-table" v-html="patient.comment"></td>
                        <td>{{ patient.phone }}</td>
                        <td>{{ patient.birthday }}</td>
                        <td>{{ patient.age }}</td>
                        <td>{{ patient.gender ? 'М' : 'Ж' }}</td>
                        <td v-for="block in blocks">
                            {{patient[block.key]}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/vue3";
import AuthLayout from "../../Layouts/AuthLayout.vue";

export default {
    components: {AuthLayout, Head, Link},
    props: ['patients'],
    layout: AuthLayout,
    data: function () {
        return {
            blocks: [
                {label: 'Жалобы и история настоящего заболевания', key: 'morbi', headerWidth: 500},
                {label: 'История перенесённых заболеваний и хирургическая история', key: 'vitae', headerWidth: 800},
                {label: 'Данные дополнительных методов исследования, заключения консультантов', key: 'lab_workup', headerWidth: 900},
                {label: 'Диагноз основного заболевания', key: 'diagnosis', headerWidth: 600},
                {label: 'Код по МКБ10', key: 'mkb', headerWidth: 500},
                {label: 'Лечение и проведённые процедуры', key: 'treatment', headerWidth: 500},
                {label: 'Комментарий', key: 'comment', headerWidth: 400},
            ]
        }
    },
}
</script>
