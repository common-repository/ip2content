import ShortcodeApi from "./ShortcodeApi";
import ConditionsApi from "./ConditionsApi";
import ConditionsContentApi from "./ConditionsContentApi";

export default {
    shortcode: new ShortcodeApi(),
    conditions: new ConditionsApi(),
    conditionsContent: new ConditionsContentApi(),
};
