require 'sinatra'
require 'digest'
require 'base64'

get '/' do
  open("./view/index.html", 'r').read()
end

get '/upload' do
  open("./view/upload.html", 'r').read()
end

post '/upload' do
  unless params[:file] && params[:file][:tempfile] && params[:file][:filename] && params[:file][:filename].split('.')[-1] == 'png'
    return "<script>alert('error');location.href='/upload';</script>"
  end
  begin
    filename = Digest::MD5.hexdigest(Time.now.to_i.to_s + params[:file][:filename]) + '.png'
    open(filename, 'wb') { |f|
      f.write open(params[:file][:tempfile],'r').read()
    }
    "Upload success, file stored at #{filename}"
  rescue
    'something wrong'
  end

end

get '/convert' do
  open("./view/convert.html", 'r').read()
end

post '/convert' do
  begin
    unless params['file']
      return "<script>alert('error');location.href='/convert';</script>"
    end

    file = params['file']
    unless file.index('..') == nil && file.index('/') == nil && file =~ /^(.+)\.png$/
      return "<script>alert('dont hack me');</script>"
    end
    res = open(file, 'r').read()
    headers 'Content-Type' => "text/html; charset=utf-8"
    "var img = document.createElement(\"img\");\nimg.src= \"data:image/png;base64," + Base64.encode64(res).gsub(/\s*/, '') + "\";\n"
  rescue
    'something wrong'
  end
end