import i18n from "../../config/plugins/i18n";

const required =
    (valueField = "value") =>
    (v) =>
        (Array.isArray(v) && v.length > 0) ||
        (typeof v === "object" && !!v && (!!v.value || !!v[valueField])) ||
        (typeof v !== "object" && !!v) ||
        i18n.t("errors.required");

const exact = (number) => (v) =>
    !v ||
    v.length === number ||
    i18n.t("errors.exact_length", { length: number });

const maxLength = (number) => (v) =>
    !v || v.length <= number || i18n.t("errors.max_length", { length: number });

const minLength = (number) => (v) =>
    !v || v.length >= number || i18n.t("errors.min_length", { length: number });

const isNumber = () => (v) => !v || !isNaN(v) || i18n.t("errors.is_number");

const minNumber = (number) => (v) =>
    !v ||
    Number(v) >= number ||
    i18n.t("errors.min_number", { number: number });

const maxNumber = (number) => (v) =>
    !v ||
    Number(v) <= number ||
    i18n.t("errors.max_number", { number: number });

const email = () => {
    const emailValidation =
        /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    // const emailValidation = /^[^@]+@\w+(\.\w+)+\w$/;

    return (v) =>
        !v || emailValidation.test(v) || i18n.t("errors.invalid_email");
};

export default {
    exact,
    minLength,
    maxLength,
    required,
    isNumber,
    minNumber,
    maxNumber,
    email,
};
