class ErrorHandler {
    constructor(ignoredErrors, response) {
        this.ignoredErrors = ignoredErrors;
        this.response = response;
        this.errors = {
            301: this.movedPermanently,
            303: this.seeOther,
            400: this.badRequest,
            402: this.paymentRequired,
            403: this.forbidden,
            404: this.notFound,
            405: this.methodNotAllowed,
            408: this.timeOut,
            422: this.formValidation,
            426: this.passwordExpired,
            429: this.TooManyRequests,
            500: this.serverError,
            unknown: this.unknown,
        };
    }

    async handle() {
        const status = this.response?.status || "unknown";

        if (
            this.ignoredErrors.includes(status) ||
            !this.errors.hasOwnProperty(status)
        ) {
            await Promise.resolve();
        } else {
            await this.errors[status].bind(this)();
        }
    }

    async badRequest() {
        // await this.router.push("/error/404");
    }

    async seeOther() {
        try {
            // await this.router.push(JSON.parse(this.response.response).redirect);
        } catch (e) {
            await Promise.resolve();
        }
    }

    async forbidden() {
        // await this.router.push({
        //   path: "/error/403",
        //   query: {
        //     message: ""
        //   }
        // });
    }

    async notFound() {
        // await this.router.push("/error/404");
    }

    async unknown() {
        // await this.router.push("/error/unknown");
    }

    async methodNotAllowed() {
        //  await this.router.push("/error/404");
    }

    async TooManyRequests() {
        //  await this.router.push("/error/404");
    }

    async timeOut() {
        //  await this.router.push("/error/404");
    }

    async formValidation() {
        // await this.router.push("/error/404");
    }

    async passwordExpired() {
        // await this.router.push("/error/404");
    }

    async serverError() {
        // await this.router.push("/error/500");
    }

    async movedPermanently() {
        // await this.router.push("/error/404");
    }

    async paymentRequired() {
        //  await this.router.push("/billings/overview");
    }
}

export default ErrorHandler;
