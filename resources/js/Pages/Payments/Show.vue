<template>
    <Head>
        <title>История выплат</title>
    </Head>

    <div class="content-header">
        <div class="container">
            <h1 class="m-0">История выплат доктора {{doctor.name}}</h1>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <Link :href="route('payments.index')" class="mb-3 d-inline-block">
                        Вернуться к списку выплат
                    </Link>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Пациент</th>
                            <th>Сумма</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(payment, index) in payments.data">
                            <td>{{ ((payments.meta.current_page - 1) * payments.meta.per_page) + index + 1 }}</td>
                            <td>
                                <Link :href="route('patients.show', payment.patient.id)">{{ payment.patient.name }}</Link>
                            </td>
                            <td>{{ payment.amount }}</td>
                            <td>{{ payment.created_at }}</td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer clearfix" v-if="payments.meta.last_page > 1">
                    <pagination :links="payments.meta.links"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Head, Link} from "@inertiajs/vue3";
import Pagination from "../../Shared/Pagination.vue";

export default {
    components: {Pagination, Head, Link},
    props: ['payments', 'doctor']
}
</script>
