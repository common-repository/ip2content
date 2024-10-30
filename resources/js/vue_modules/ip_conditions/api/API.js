import ConditionsApi from "./ConditionsApi";
import ConditionsContentApi from "./ConditionsContentApi";
import FieldsApi from "./FieldsApi";
import SettingsApi from "./SettingsApi";
import LicensesApi from "./LicensesApi";
import StatisticsApi from "./StatisticsApi";

export default {
    conditions: () => new ConditionsApi(),
    fields: () => new FieldsApi(),
    conditionsContent: () => new ConditionsContentApi(),
    settings: () => new SettingsApi(),
    licenses: () => new LicensesApi(),
    statistics: () => new StatisticsApi(),
};
