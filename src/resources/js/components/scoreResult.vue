<template>
    <div>
        <div id="result_table" class="flex-item table-responsive" v-on:change="setS3Score">
        </div>
        <p>{{score_table}}</p>
        <!-- ここにAjaxでs3のデータが表示される -->
        <!-- changeメソッドでsetS3Scoreを発火させる        最終的にdisplay: noneで非表示にする -->
        <!-- <table class="table text-center table-condensed">
            <tbody v-for="(score_row,row_index) in score_table" v-bind:key="row_index">
                <tr v-for="(score_column, column_index) in score_row" v-bind:key="column_index">
                </tr>
            </tbody>
        </!-->
    </div>
</template>

<script>
    export default {
        components: {
        },
        props: {
            score_table: '',
        },
        data() {
            return {
                score_table: '',
            };
        },
        methods: {
            setS3Score() {
                // this.score_table = $('#result_table').text();
                // console.log(this.score_table);
            },

            deleteRow(row_no) {
                // console.log(row_no)
                // this.users.splice(row_no,1)
            }
        },
        // 一回だけの変更を反映してくれれば良いので、computedにする
        // computed: {
        //     score_table: function() {
        //         console.log('watch 発火');
        //     }
        // },
    };
</script>

<style>
</style>