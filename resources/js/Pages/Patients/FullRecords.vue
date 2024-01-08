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
                        <th width="250">Ф.И.О</th>
                        <th width="250">Место проживания</th>
                        <th width="500">Заметка</th>
                        <th width="500">Комментарий</th>
                        <th>Номер телефона</th>
                        <th>Дата рождения</th>
                        <th>Возраст</th>
                        <th>Пол</th>
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
    layout: AuthLayout
}
</script>
