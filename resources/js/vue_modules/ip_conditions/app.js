import Vue from "vue";
import Main from "./Main";
import vuetify from "./config/plugins/vuetify";
import i18n from "./config/plugins/i18n";
import store from "./store/index";
import Api from "./api/API";
import Rules from "./services/helpers/form-rules";
import moment from "moment";

Vue.prototype.$API = Api;
Vue.prototype.$formRules = Rules;
Vue.prototype.$moment = moment;

const storageLanguage = localStorage.getItem("wmip2c_language");
if (storageLanguage) {
    i18n.locale = storageLanguage;
}

export default new Vue({
    store,
    vuetify,
    i18n,
    render: (h) => h(Main),
}).$mount("#ip_conditions_vue");
