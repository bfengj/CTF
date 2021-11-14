const puppeteer = require('puppeteer')
const path = require('path')
const morgan = require('morgan')
const express = require('express')
const app = express()

app.use(morgan('combined'))


app.get('/query/:id', async (req, res) => {
  const url = new Buffer(req.params.id, 'base64').toString()
  res.end(url)

  const browser = await puppeteer.launch({ headless: true,  args: [
    '--no-sandbox',
  ]
 });
  const page = await browser.newPage()

  page.on('dialog', dialog => dialog.dismiss()) // onbeforeunload

  // await page.setRequestInterception(true)
  await page.waitFor(500);

  try {
    await page.goto(url, { timeout: 5000 })
  } catch (e) {
    // console.log(e);
  }
  await page.waitFor(500);
  await page.close()
  await browser.close()

})

app.listen(3000)


