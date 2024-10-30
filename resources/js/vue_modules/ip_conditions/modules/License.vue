<script>
export default {
    name: "License",

    data: () => ({
        licenses: {
            ip2c: "",
            tracking: "",
        },
    }),

    async beforeMount() {
        this.licenses = await this.$API.licenses().get();
    },

    methods: {
        async save() {
            try {
                await this.$API.licenses().create(this.licenses);
                await this.$store.dispatch(
                    "alert/showSuccess",
                    this.$t("alert.saved")
                );

                setTimeout(() => {
                    location.reload();
                }, 1000);
            } catch (e) {
                console.log(e);
                await this.$store.dispatch("alert/showError", e.message);
            }
        },
    },
};
</script>

<template>
    <div id="tabs_license">
        <h1>
            <b>{{ $t("licenses.title") }}</b>
        </h1>
        <br />
        <v-container class="license_container">
            <v-row class="license_row">
                <v-col>
                    {{ $t("licenses.ip2c.title") }}
                    <br />
                    <br />
                    <v-text-field
                        v-model="licenses.ip2c"
                        outlined
                    ></v-text-field>
                    <br />
                    {{ $t("licenses.ip2c.get_token") }}
                    <span class="orange_text_color"
                        ><a :href="$t('licenses.ip2c.url')" target="_blank">{{
                            $t("licenses.ip2c.link")
                        }}</a></span
                    >
                </v-col>
            </v-row>
            <v-row class="license_row">
                <v-col>
                    LeadLab Tracking Code
                    <br />
                    <br />
                    <v-text-field
                        v-model="licenses.tracking"
                        outlined
                    ></v-text-field>
                    <br />
                    {{ $t("licenses.tracking.get_token") }}
                    <span class="orange_text_color"
                        ><a
                            :href="$t('licenses.tracking.url')"
                            target="_blank"
                            >{{ $t("licenses.tracking.link") }}</a
                        ></span
                    >
                </v-col>
            </v-row>
        </v-container>
        <br /><br />
        <v-btn @click="save" class="save_licenses">{{
            $t("buttons.save")
        }}</v-btn>
    </div>
</template>

<style lang="scss">
$grayColor: #f3f3f3;
$grayBorderColor: #dfdfdf;
$grayTextColor: #707070;
$orangeColor: #f69d32;

#tabs_license {
    max-width: 600px;
    margin: 50px 100px;
    color: $grayTextColor;

    .orange_text_color,
    .orange_text_color a {
        color: $orangeColor;
    }

    .licenses_faq,
    .save_licenses {
        color: white;
        background-color: $orangeColor;
        font-weight: bold;
    }

    .license_container {
        color: $grayTextColor;
        font-weight: bold;

        .license_row {
            margin: 15px 0;
            padding: 20px;
            background-color: $grayColor;
        }
    }
}

@media screen and (max-width: 960px) {
    #tabs_license {
        margin: 50px 50px;
    }
}
</style>
