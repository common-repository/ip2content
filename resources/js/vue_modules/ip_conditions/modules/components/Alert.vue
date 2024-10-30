<script>
export default {
    name: "Alert",

    data: () => ({
        config: {
            warning: {
                color: "warning",
                icon: "mdi-alert-circle",
            },
            success: {
                color: "success",
                icon: "mdi-checkbox-marked-circle",
            },
            error: {
                color: "error",
                icon: "mdi-alert",
            },
            info: {
                color: "primary",
                icon: "mdi-information",
            },
        },
    }),

    computed: {
        show() {
            return this.$store.getters["alert/show"];
        },
        messages() {
            return this.$store.getters["alert/messages"];
        },
    },

    methods: {
        clickHandler(message, index) {
            if (message.hasOwnProperty("onClick")) {
                message.onClick();
            }
            this.hide(index);
        },
        hide(index) {
            this.$store.dispatch("alert/hide", index);
        },
    },
};
</script>

<template>
    <div class="alert-container alert-title">
        <transition-group name="list-complete" tag="p">
            <div
                v-for="(message, index) in messages"
                :key="message.index"
                class="mb-5 list-complete-item"
            >
                <div class="alert alert-wrapper">
                    <v-alert
                        :color="config[message.type].color"
                        border="left"
                        class="mb-0 pr-2"
                        colored-border
                        max-width="500"
                    >
                        <div class="d-flex align-start">
                            <v-icon :color="config[message.type].color">
                                {{ config[message.type].icon }}
                            </v-icon>
                            <div v-if="message.list && message.list.length">
                                <div
                                    class="subtitle-1 font-weight-bold pl-2 mb-1"
                                >
                                    {{ message.title }}
                                </div>
                                <v-row class="mt-1 pb-2">
                                    <v-col
                                        v-for="(
                                            listItem, itemIndex
                                        ) in message.list"
                                        :key="itemIndex"
                                        :class="{
                                            'col-md-6':
                                                message.list.length > 10,
                                        }"
                                        class="body-2 py-0"
                                        cols="12"
                                    >
                                        - {{ listItem }}
                                    </v-col>
                                </v-row>
                            </div>
                            <span
                                v-else
                                :class="{ 'cursor-pointer': message.onClick }"
                                class="dark_blue--text pl-3"
                                @click="clickHandler(message, index)"
                                >{{
                                    message.text || "Unknown server error"
                                }}</span
                            >
                            <v-spacer></v-spacer>
                            <v-icon @click="hide(index)"> mdi-close </v-icon>
                        </div>
                    </v-alert>
                </div>
            </div>
        </transition-group>
    </div>
</template>

<style scoped>
.alert-container {
    position: fixed;
    bottom: 200px;
    right: 70px;
    z-index: 9999999;
    transition: all 0.3s;
}

.alert {
    box-shadow: 0 11px 15px rgba(0, 0, 0, 0.2);
}

.list-complete-item {
    transition: all 0.4s;
    margin-bottom: 20px;
}

.list-complete-enter,
.list-complete-leave-to {
    opacity: 0;
    transform: translateX(200px);
}

.list-complete-leave-active {
    position: relative;
}
</style>
