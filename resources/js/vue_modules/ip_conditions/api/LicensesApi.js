import APIModel from "./APIModel";

export default class LicensesApi extends APIModel {
    constructor() {
        super("/licenses");
    }
}
