<script>
export default {
    name: "ToggleSwitch",
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        width: {
            type: Number,
            default: 50,
        },
        height: {
            type: Number,
            default: 30,
        },
        title: {
            type: String,
            default: function () {
                return this.$t("hints.toggle_status");
            },
        },
    },

    data: () => ({
        id: "toggle_switch",
        model: false,
    }),

    beforeMount() {
        this.id += "_" + Math.trunc(Math.random() * 1e5);
    },

    watch: {
        value: {
            immediate: true,
            handler(v) {
                if (v === this.model) {
                    return;
                }
                this.model = v;
            },
        },
    },

    methods: {
        emitChanged() {
            this.$emit("input", this.model);
        },
    },
};
</script>

<template>
    <div
        class="toggle_switch"
        :title="title"
        :style="{
            '--width': width + 'px',
            '--height': height + 'px',
        }"
    >
        <input type="checkbox" :id="id" v-model="model" @change="emitChanged" />
        <label :for="id"></label>
    </div>
</template>

<style lang="scss">
.toggle_switch {
    input[type="checkbox"] {
        display: none;

        &:checked {
            & + label:before {
                width: var(--width);
                background-color: rgba(76, 217, 100, 1);
                transition: width 0.2s cubic-bezier(0, 0, 0, 0.1) !important;
            }

            & + label:after {
                left: calc(
                    var(--width) - var(--bulb-gutter) - var(--bulb-size)
                );
            }

            & + label {
                box-shadow: inset 0 0 0 25px #e4e4e4;
                transition: box-shadow 2.5s cubic-bezier(0, 1.2, 0.94, 0.95);
            }
        }
    }

    label {
        user-select: none;
        transition: 0.2s ease;
        display: inline-block;
        height: var(--height);
        width: var(--width);
        position: relative;
        background-color: red;
        border-radius: calc(var(--height) / 2);
        overflow: hidden;

        --bulb-gutter: 3px;
        --bulb-size: calc(var(--height) - var(--bulb-gutter) * 2);

        &:before {
            content: "";
            position: absolute;
            display: block;
            height: var(--height);
            top: 0;
            left: 0;
            border-radius: calc(var(--height) / 2);
            background-color: rgba(76, 217, 100, 0);
            transition: 0.2s cubic-bezier(0.24, 0, 0.5, 1);
        }

        //  Bulb
        &:after {
            content: "";
            position: absolute;
            display: block;
            height: var(--bulb-size);
            width: var(--bulb-size);
            top: var(--bulb-gutter);
            left: var(--bulb-gutter);
            border-radius: calc(var(--height) / 2);
            background: #fff;
            box-shadow: 0 0 0 1px hsla(0, 0%, 0%, 0.1),
                0 4px 0 0 hsla(0, 0%, 0%, 0.04), 0 4px 9px hsla(0, 0%, 0%, 0.13),
                0 3px 3px hsla(0, 0%, 0%, 0.05);
            transition: 0.35s cubic-bezier(0.54, 1.6, 0.5, 1);
        }
    }
}
</style>
