<script>
import Vue from "vue";

export default Vue.extend({
    name: "Paginator",

    props: {
        value: {
            type: Object,
            required: true,
        },
        itemsPerPageOptions: {
            type: Array,
            required: true,
        },
    },

    data: () => ({
        pagination: {
            page: 1,
            per_page: 0,
            last_page: 0,
            total_items: 0,
        },
    }),

    watch: {
        value: {
            deep: true,
            immediate: true,
            handler() {
                this.pagination = this.value;
            },
        },
    },

    methods: {
        prevPage() {
            this.pagination.page -= 1;
            this.updatePageEmit();
        },
        toFirstPage() {
            this.pagination.page = 1;
            this.updatePerPageEmit();
        },
        nextPage() {
            this.pagination.page += 1;
            this.updatePageEmit();
        },
        changePageTo(event) {
            this.pagination.page = Number(event.target.value);
            this.updatePageEmit();
        },
        updatePageEmit() {
            this.$emit("updatePage");
        },
        updatePerPageEmit() {
            this.$emit("updatePerPage");
        },
    },
});
</script>

<template>
    <div class="v-data-footer custom-data-footer d-flex">
        <div v-if="pagination.total_items">
            <v-btn
                :disabled="pagination.page < 2"
                class="mx-1"
                fab
                text
                x-small
                @click="prevPage()"
            >
                <v-icon>mdi-chevron-left</v-icon>
            </v-btn>
            <input
                v-model="pagination.page"
                class="paginator-input"
                type="number"
                min="1"
                :max="pagination.last_page"
                @input="changePageTo"
            />
            <span class="mx-2">/</span>
            <span>{{ pagination.last_page }}</span>
            <v-btn
                :disabled="pagination.page >= pagination.last_page"
                class="mx-1"
                fab
                text
                x-small
                @click="nextPage()"
            >
                <v-icon>mdi-chevron-right</v-icon>
            </v-btn>
        </div>
        <div :class="{ 'ml-3': !pagination.total_items }">
            <strong>{{ pagination.total_items }}</strong>
            <span> {{ $t("paginator.results") }}</span>
        </div>
        <v-spacer></v-spacer>
        <div v-if="pagination.total_items" class="d-flex">
            <div style="padding-top: 5px">
                <span>{{ $t("paginator.rows_per_page") }} </span>
            </div>

            <div style="width: 90px; padding: 0 15px">
                <v-select
                    v-model="pagination.per_page"
                    :items="itemsPerPageOptions"
                    class="font-weight-bold"
                    hide-details
                    dense
                    @change="toFirstPage()"
                ></v-select>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.paginator-input {
    background: #fff;
    border: thin solid #e8e9ee !important;
    border-radius: 4px;
    height: 22px;
    width: 65px;
    text-align: center;
    padding: 0;
}

.custom-data-footer {
}

.v-data-footer {
    background: var(--v-table-header-color-base);
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
}
</style>
