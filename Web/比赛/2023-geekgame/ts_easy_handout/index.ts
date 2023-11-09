import { opine, json } from 'https://deno.land/x/opine@2.3.4/mod.ts'
import staticFiles from 'https://deno.land/x/static_files@1.1.6/mod.ts'

const FLAG1 = (await Deno.readTextFile('/Users/feng/github/CTF/Web/比赛/2023-geekgame/ts_easy_handout/flags/flag1')).trim()
const FLAG2 = (await Deno.readTextFile('/Users/feng/github/CTF/Web/比赛/2023-geekgame/ts_easy_handout/flags/flag2')).trim()

const PREPEND_SOURCE = `
type flag1 = '${FLAG1}'
type flag2 = object | { new (): { v: () => (a: (a: unknown, b: { '${FLAG2}': never } & Record<string, string>) => never) => unknown } }
`

function checkSource(source: unknown): source is string {
  if (typeof source !== 'string') {
    return false
  }
  if (source.length > 2048) {
    return false
  }
  return true
}

async function runSource(source: string): Promise<string> {
  console.log('Running source:', source)
  const controller = new AbortController()
  const command = new Deno.Command(Deno.execPath(), {
    args: ['run', '--no-prompt', '--check', '-'],
    signal: controller.signal,
    stdin: 'piped',
    stdout: 'piped',
    stderr: 'piped'
  })
  setTimeout(() => controller.abort(), 1000)
  const process = command.spawn()
  const writer = process.stdin.getWriter()
  await writer.write(new TextEncoder().encode(PREPEND_SOURCE))
  await writer.write(new TextEncoder().encode(source))
  await writer.close()
  const { code, stdout, stderr } = await process.output()
  return (
    `Process exited with code ${code}\n` +
    `[+] Stdout:\n${new TextDecoder().decode(stdout)}\n` +
    `[+] Stderr:\n${new TextDecoder().decode(stderr)}`
  )
}

function checkOutput(output: string): string {
  /*if (/flag/i.test(output)) {
    console.log(`Filtered bad output: ${output}`)
    return '绷'
  }
  if (output.length > 512) {
    console.log(`Filtered long output: ${output}`)
    return '乐'
  }*/
  return output
}

const app = opine()

app.use(json())

app.use(staticFiles('static'))

app.post('/api/run', async (req, res) => {
  const { source } = req.body
  if (!checkSource(source)) {
    return res.sendStatus(400)
  }
  let output = await runSource(source)
  output = checkOutput(output)
  res.json({ output })
})

app.listen(3000)
