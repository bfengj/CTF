package com.ctf.easy_java1.util;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

public class shellUtil {
    public static String runCommand(String command)throws Exception{
        Process process = null;
        StringBuilder result = new StringBuilder();
        List<String> processList = new ArrayList<String>();
        process =   Runtime.getRuntime().exec(command);
        BufferedReader input = new BufferedReader(new InputStreamReader(process.getInputStream()));
        String line = "";
        while ((line = input.readLine()) != null) {
            processList.add(line);
        }
        input.close();

        for (String line2 : processList) {
            result.append(line2).append("\n");
        }
        return result.toString();
    }
}
