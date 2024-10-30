import APIModel from "./APIModel";

export default class ConditionsContentApi extends APIModel {
    constructor() {
        super("/conditions/contents");
        this.useWordpressRouteNamespace();
    }
}
