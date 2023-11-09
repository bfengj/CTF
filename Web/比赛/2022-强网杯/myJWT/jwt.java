import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;
import java.security.KeyPair;
import java.security.interfaces.ECPrivateKey;
import java.security.interfaces.ECPublicKey;
import java.security.*;
import java.util.Base64;
import java.util.Scanner;

import com.alibaba.fastjson.*;

class ECDSA{
	public KeyPair keyGen() throws Exception {
		KeyPairGenerator keyPairGenerator = KeyPairGenerator.getInstance("EC");
		keyPairGenerator.initialize(384);
		KeyPair keyPair = keyPairGenerator.genKeyPair();
		return keyPair;
	}
	
	public byte[] sign(byte[] str, ECPrivateKey privateKey) throws Exception {
		Signature signature = Signature.getInstance("SHA384withECDSAinP1363Format");
		signature.initSign(privateKey);
		signature.update(str);
		byte[] sig = signature.sign();
		return sig;
	}
	
	public boolean verify(byte[] sig, byte[] str ,ECPublicKey publicKey) throws Exception {
		Signature signature = Signature.getInstance("SHA384withECDSAinP1363Format");
		signature.initVerify(publicKey);
		signature.update(str);
		return signature.verify(sig);
	}
}

public class jwt{
	
	public static int EXPIRE = 60;
	public static ECDSA ecdsa = new ECDSA();
	public static String generateToken(String user, ECPrivateKey ecPrivateKey) throws Exception {
		JSONObject header = new JSONObject();
		JSONObject payload = new JSONObject();
		header.put("alg", "myES");
		header.put("typ", "JWT");
		String headerB64 = Base64.getUrlEncoder().encodeToString(header.toJSONString().getBytes());
		payload.put("iss", "qwb");
		payload.put("exp", System.currentTimeMillis() + EXPIRE * 1000);
		payload.put("name", user);
		payload.put("admin", false);
		String payloadB64 = Base64.getUrlEncoder().encodeToString(payload.toJSONString().getBytes());
		String content = String.format("%s.%s", headerB64, payloadB64);
		byte[] sig = ecdsa.sign(content.getBytes(), ecPrivateKey);
		String sigB64 = Base64.getUrlEncoder().encodeToString(sig);
		
		return String.format("%s.%s", content, sigB64);
	}
	
	public static boolean verify(String token, ECPublicKey ecPublicKey) throws Exception {
		String[] parts = token.split("\\.");
		if (parts.length != 3) {
			return false;
		}else {
			String headerB64 = parts[0];
			String payloadB64 = parts[1];
			String sigB64 = parts[2];
			String content = String.format("%s.%s", headerB64, payloadB64);
			byte[] sig = Base64.getUrlDecoder().decode(sigB64);
			return ecdsa.verify(sig, content.getBytes(), ecPublicKey);
		}
		
	}
	
	public static boolean checkAdmin(String token, ECPublicKey ecPublicKey, String user) throws Exception{
		if(verify(token, ecPublicKey)) {
			String payloadB64 = token.split("\\.")[1];
			String payloadDecodeString = new String(Base64.getUrlDecoder().decode(payloadB64));
			JSONObject payload = JSON.parseObject(payloadDecodeString);
			
			if(!payload.getString("name").equals(user)) {
				return false;
			}
			if (payload.getLong("exp") < System.currentTimeMillis()) {
				return false;
			}
			return payload.getBoolean("admin");
		} else {
			return false;
		}	
	}
	
	public static String getFlag(String token, ECPublicKey ecPublicKey, String user) throws Exception{
		String err = "You are not the administrator.";
		if(checkAdmin(token, ecPublicKey, user)) {
			File file = new File("/root/task/flag.txt");
			BufferedReader br = new BufferedReader(new FileReader(file));
			String flag = br.readLine();
			br.close();
			return flag;
		} else {
			return err;
		}
	}
	
	public static boolean task() throws Exception {
		Scanner input = new Scanner(System.in);
		System.out.print("your name:");
		String user = input.nextLine().strip();
		System.out.print(String.format("hello %s, let's start your challenge.\n", user));
		KeyPair keyPair = ecdsa.keyGen();
		ECPrivateKey ecPrivateKey = (ECPrivateKey) keyPair.getPrivate();
		ECPublicKey ecPublicKey = (ECPublicKey) keyPair.getPublic();
		String menu = "1.generate token\n2.getflag\n>";
		Integer choice = 0;
		Integer count = 0;
		while (count <= 10) {
			count++;
			System.out.print(menu);
			choice = Integer.parseInt(input.nextLine().strip());
			if(choice == 1) {
				String token = generateToken(user, ecPrivateKey);
				System.out.println(token);
			} else if (choice == 2) {
				System.out.print("your token:");
				String token = input.nextLine().strip();
				String flag = getFlag(token, ecPublicKey, user);
				System.out.println(flag);
				input.close();
				break;
			} else {
				input.close();
				break;
			}
		}
		return true;
	}
	
	public static void main(String[] args) throws Exception {
		task();
	}
	
}