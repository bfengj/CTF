// This is a part source code of bot.js on the server.
// You don't need all. So I delete some unnecessary codes.

try {
    browser = await puppeteer.launch({
        headless: true,
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
        ],
        timeout: 20000,
    });
    page = await browser.newPage();

    await page.goto(challURL + "/user/login", {
        timeout: 2000,
    });

    await page.type("#username", "admin", {
        delay: 100,
    });

    await page.type("#password", ADMIN_PASS, {
        delay: 100,
    });

    await page.click("#submit");

    await new Promise((resolve) => setTimeout(resolve, 2e3));

    await page.goto(url, {
        timeout: 2000,
    });

    await new Promise((resolve) => setTimeout(resolve, 3e3));

    let r = await page.goto(challURL + "/admin/paste/" + hash, {
        timeout: 2000,
    });
    
    await page.waitForSelector("#send");

    await page.click("#send");

    await page.waitForSelector("#remarks");

    await page.type("#remarks", FLAG, {
        delay: 200,
    });

    await page.click("#submit");
    await new Promise((resolve) => setTimeout(resolve, 3e3));
    console.log(`[-] done: ${url}`)

    await page.close();
    await browser.close();
    page = null;
    browser = null;
} catch (err) {
    console.log(err);
} finally {
    if (page) await page.close();
    if (browser) await browser.close();
}

