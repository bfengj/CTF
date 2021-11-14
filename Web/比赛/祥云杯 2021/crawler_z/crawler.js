const zombie = require('zombie');


class Crawler {
    constructor(options) {
        this.crawler = new zombie({
            userAgent: options.userAgent,
            referrer: options.referrer,
            silent: true,
            strictSSL: false
        });
    }

    goto(url) {
        return new Promise((resolve, reject) => {
            try {
                this.crawler.visit(url, () => {
                    const resource = this.crawler.resources.length
                        ? this.crawler.resources.filter(resource => resource.response).shift() : null;
                    this.statusCode = resource.response.status
                    this.headers = this.getHeaders();
                    this.cookies = this.getCookies();
                    this.htmlContent = this.getHtmlContent();
                    resolve();
                });
            } catch (err) {
                reject(err.message);
            }
        })
    }

    close() {
        return new Promise((resolve, reject) => {
            try {
                resolve(this.crawler.destroy());
            } catch (err) {
                reject(err.message);
            }
        });
    }

    getCookies() {
        const cookies = [];
    
        if (this.crawler.cookies) {
          this.crawler.cookies.forEach(cookie => cookies.push({
            name: cookie.key,
            value: cookie.value,
            domain: cookie.domain,
            path: cookie.path,
          }));
        }
    
        return cookies;
    }
    
    getHeaders() {
        const headers = new Map();

        const resource = this.crawler.resources.length
            ? this.crawler.resources.filter(_resource => _resource.response).shift() : null;

        if (resource) {
            resource.response.headers._headers.forEach((header) => {
                if (!headers[header[0]]) {
                    headers[header[0]] = [];
                }
                headers[header[0]].push(header[1]);
            });
        }
        return headers;
    }

    getHtmlContent() {
        let html = '';
        if (this.crawler.document && this.crawler.document.documentElement) {
            try {
                html = this.crawler.html();
            } catch (error) {
                console.log(error);
            }
        }
        return html;
    }
}





module.exports = Crawler;