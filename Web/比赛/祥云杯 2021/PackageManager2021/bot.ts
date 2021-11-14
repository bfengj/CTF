import * as puppeteer from 'puppeteer';
import { Report } from './schema';


const TIMEOUT = 5000;

const timeout = (delay: number) => {
    return new Promise(resolve => setTimeout(resolve, delay));
}

async function browse(id: string, base: string) {
    const browser = await puppeteer.launch({
        args: [
            '--headless',
            '--disable-gpu',
            '--disable-dev-shm-usage'
        ],
        product: 'firefox'
    });
    try {
        const page = await browser.newPage();
        page.on('dialog', async dialog => {
            await dialog.accept();
        });
        console.log('Working');
        await page.goto(new URL('/login', base).toString());
        await page.type('#username', 'admin');
        await page.type('#password', process.env.ADMIN_PASSWORD);
	await Promise.all([page.waitForNavigation(), page.click('#submit')]);

	console.log(`Checking packages ${id}`)
        await page.goto(new URL(`/packages/${id}`, base).toString());
        await timeout(TIMEOUT * 4);

    } catch (err) {
        console.log(err)
    } finally {
        await browser.close()
    }
}

(async () => {
    console.log("Starting bot......")
    while (true) {
        const reports = await Report.find({})
        console.log(`Reports length : ${reports.length}`);
        await Report.deleteMany({});
        for (let report of reports) {
            console.log(report.pack_id)
            await browse(report.pack_id, process.env.SITE_URL)
        }
        await timeout(TIMEOUT);
    }
})();
