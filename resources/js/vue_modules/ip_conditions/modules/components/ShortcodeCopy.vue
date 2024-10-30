<script>
import ShortcodeConfig from "../../config/ShortcodeConfig.json";

export default {
    name: "ShortcodeCopy",

    props: {
        conditionId: {
            type: Number,
            required: true,
        },
        copy: {
            type: Boolean,
            default: false,
        },
    },

    data: () => ({
        shortcodeText: ShortcodeConfig.text,
        showDialog: false,
        dialogId: null,
    }),

    watch: {
        copy: {
            immediate: true,
            handler(value) {
                if (!value) {
                    return;
                }

                this.copyShortcode();
            },
        },
    },

    methods: {
        copyShortcode() {
            try {
                navigator.clipboard.writeText(
                    `<div class="ip_condition_content" data-id="${this.conditionId}"></div>`
                );
                this.$store.dispatch(
                    "alert/showSuccess",
                    this.$t("alert.copied")
                );
            } catch (e) {
                this.dialogId = this.conditionId;
                this.showDialog = true;
            }
        },
    },
};
</script>

<template>
    <div style="display: inline-block">
        <div class="shortcode_copy">
            <slot name="activator"></slot>
        </div>
        <v-dialog v-model="showDialog" width="450">
            <v-card>
                <v-card-title>{{
                    $t("conditions.shortcode_dialog.title")
                }}</v-card-title>
                <v-card-subtitle class="pt-2">{{
                    $t("conditions.shortcode_dialog.subtitle")
                }}</v-card-subtitle>
                <v-card-text class="text-center">
                    <b class="condition_shortcode">
                        {{ shortcodeText.replace("{id}", dialogId) }}
                    </b>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn @click="showDialog = false" class="orange_btn">{{
                        $t("buttons.close")
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<style scoped lang="scss">
$grayColor: #f3f3f3;
$orangeColor: #f69d32;

.condition_shortcode {
    user-select: all;
}

.orange_btn {
    color: white !important;
    background-color: $orangeColor !important;
    font-weight: bold !important;
}
</style>
