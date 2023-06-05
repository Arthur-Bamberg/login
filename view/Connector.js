export class Connector {
    constructor(controllerName) {
        const currentUrl = window.location.href;
        const paths = currentUrl.split('/');

        paths.splice(-3);

        this.url = paths.join('/') + '/controllers/' + controllerName + '.php';
    }

    async getRequest(methodName, getParams = null) {
        try {
            const getUrl = this.url + '?method=' + methodName + (getParams === null ? '' : getParams);

            const response = await fetch(getUrl);
            if (response.ok) {
                const data = await response.json();
                return data;
            } else {
                throw new Error('Request failed.');
            }
        } catch (error) {
            return 'Error:' + error.message;
        }
    }

    async postRequest(data) {
        try {
            const response = await fetch(this.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                const responseData = await response.json();
                return responseData;
            } else {
                throw new Error('Request failed.');
            }
        } catch (error) {
            return 'Error:' + error.message;
        }
    }
}