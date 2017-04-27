import java.io.*;
import java.net.*;
import java.util.Scanner;

public class BlockchainClient {
final static String URL = "http://172.16.198.132:7050/chaincode";
    final static String POLL_NAME = "ChaincodeLogSmartContract";
    static Scanner kb = new Scanner(System.in);

    public static void main(String[] args) {
        boolean end = false;
        
        while (!end) {
            System.out.println("\t1. Init");
            System.out.println("\t2. Log Request");
            System.out.println("\t3. Query");
            System.out.println("\t4. Exit");
            System.out.print("> ");
            int input = kb.nextInt();
            kb.nextLine();

            String method = "";
            String type = "";
            String name = POLL_NAME;
            String arg = "";
            String id = "";
            boolean key = false;
            switch (input) {
                case 1:
                    method = "deploy";
                    type = "4";
                    arg = "[\"init\"";
                    key = true;
                    while (key) {
                        System.out.print("\"key\": ");
                        String nextKey = kb.nextLine();
                        if (nextKey.compareTo("e") != 0) {
                            arg += ", " + nextKey + "";
                        } else {
                            key = false;
                        }
                    }
                    arg += "]";
                    id = "1";
                    run(method, type, name, arg, id);
                    break;
                case 2:
                    method = "invoke";
                    type = "1";
                    arg = "[\"log\"";
                    key = true;
                    while (key) {
                        System.out.print("\"key\": ");
                        String nextKey = kb.nextLine();
                        if (nextKey.compareTo("e") != 0) {
                            arg += ", " + nextKey + "";
                        } else {
                            key = false;
                        }
                    }
                    arg += "]";
                    id = "2";
                    run(method, type, name, arg, id);
                    break;
                case 3:
                    method = "invoke";
                    type = "1";
                    arg = "[\"query\"";
                    key = true;
                    while (key) {
                        System.out.print("\"key\": ");
                        String nextKey = kb.nextLine();
                        if (nextKey.compareTo("e") != 0) {
                            arg += ", " + nextKey + "";
                        } else {
                            key = false;
                        }
                    }
                    arg += "]";
                    id = "3";
                    run(method, type, name, arg, id);
                    break;
                case 4:
                    end = true;
                    break;
                default:
                    System.out.println("invalid response");
            }
        }

    }

    public static void run(String method, String type, String name,
            String arg, String id) {

        try {
            String json = "{\"jsonrpc\" : \"2.0\"," +
                    "\"method\" : " + method + "," + 
                    "params : {\"type\" : " + type + "," +
                    "\"chaincodeID\" : {\"name\" : " + name + "}," +
                    "\"ctorMsg\" : {\"args\" : " + arg + "}}," + 
                    "\"id\" : " + id + "}";
            String[] cmd = {
                "curl", "-i", URL, "-X", "POST", "-H",
                "Content-Type: application/json",
                "-H", "Accept: application/json", "-d", json};
            System.out.println(json);
            ProcessBuilder processBuilder = new ProcessBuilder(cmd);
            processBuilder.redirectErrorStream(true);
            Process process = processBuilder.start();

            BufferedReader br = new BufferedReader(new InputStreamReader(process.getInputStream()));
            String line;
            while((line = br.readLine()) != null) {
                System.out.println(line);
            }
        } catch (IOException e) {}

    }
}
