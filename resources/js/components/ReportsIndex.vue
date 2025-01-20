<template>
    <div class="container">
        <h1>日報一覧</h1>

        <!-- フラッシュメッセージ -->
        <div v-if="flashMessage" class="alert alert-success">
            {{ flashMessage }}
        </div>

        <!-- フィルタ -->
        <div class="mb-4">
            <label for="user-select">ユーザー:</label>
            <select id="user-select" v-model="selectedUser" class="form-select" @change="loadReports">
                <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                </option>
            </select>

            <label for="year-month-select" class="mt-3">年月:</label>
            <select id="year-month-select" v-model="selectedMonth" class="form-select" @change="loadReports">
                <option v-for="month in months" :key="month" :value="month">
                    {{ month }}
                </option>
            </select>
        </div>

        <!-- タブ -->
        <ul class="nav nav-tabs" id="report-tabs" role="tablist">
            <li class="nav-item" v-for="day in 31" :key="day" role="presentation">
                <button
                    class="nav-link"
                    :class="{ active: currentDay === day }"
                    @click="selectDay(day)"
                >
                    {{ day }}日
                </button>
            </li>
        </ul>

        <!-- タブ内容 -->
        <div class="tab-content mt-3">
            <div v-if="currentReports.length > 0">
                <h5>{{ currentDay }}日の日報</h5>
                <ul>
                    <li v-for="task in currentReports" :key="task.id">
                        {{ task.task_name }} (予定: {{ task.planned_hours }}h, 実績: {{ task.actual_hours }}h)
                    </li>
                </ul>
            </div>
            <div v-else>
                <h5>{{ currentDay }}日の日報</h5>
                <p>この日の日報はありません。</p>
            </div>
            <button class="btn btn-success" @click="saveReports">保存</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            flashMessage: '',
            users: [], // ユーザー一覧
            selectedUser: null, // 選択されたユーザー
            months: [], // 選択可能な年月
            selectedMonth: '', // 選択された年月
            reports: {}, // 日報データ
            currentDay: 1, // 現在のタブ
        };
    },
    computed: {
        currentReports() {
            return this.reports[this.currentDay] || [];
        },
    },
    methods: {
        loadUsers() {
            // ユーザーリストの取得
            fetch('/api/users')
                .then((response) => response.json())
                .then((data) => {
                    this.users = data;
                    this.selectedUser = this.users[0]?.id || null; // 最初のユーザーを選択
                });
        },
        loadMonths() {
            // 月リストの取得
            this.months = ['2025-01', '2025-02', '2025-03']; // ダミーデータ
            this.selectedMonth = this.months[0]; // 最初の月を選択
        },
        loadReports() {
            // 日報データの取得
            if (!this.selectedUser || !this.selectedMonth) return;

            fetch(`/api/reports?user=${this.selectedUser}&month=${this.selectedMonth}`)
                .then((response) => response.json())
                .then((data) => {
                    this.reports = data;
                });
        },
        selectDay(day) {
            // タブを選択
            this.currentDay = day;
        },
        saveReports() {
            // 保存処理
            const data = this.currentReports;

            fetch(`/api/reports/save/${this.currentDay}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            })
                .then((response) => response.json())
                .then((result) => {
                    this.flashMessage = '保存が成功しました。';
                    setTimeout(() => (this.flashMessage = ''), 3000);
                })
                .catch((error) => {
                    alert('保存中にエラーが発生しました。');
                });
        },
    },
    mounted() {
        this.loadUsers();
        this.loadMonths();
        this.loadReports();
    },
};
</script>

<style scoped>
.nav-tabs .nav-link {
    cursor: pointer;
}
</style>
