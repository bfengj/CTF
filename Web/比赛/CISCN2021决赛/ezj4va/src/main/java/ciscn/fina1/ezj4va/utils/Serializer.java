package ciscn.fina1.ezj4va.utils;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.ObjectOutputStream;
import java.io.OutputStream;
import java.util.Base64;

public class Serializer {

	public static String serialize(final Object obj) throws IOException {
		final ByteArrayOutputStream out = new ByteArrayOutputStream();
		serialize(obj, out);
		return Base64.getEncoder().encodeToString(out.toByteArray());
	}

	public static void serialize(final Object obj, final OutputStream out) throws IOException {
		final ObjectOutputStream objOut = new ObjectOutputStream(out);
		objOut.writeObject(obj);
	}

}