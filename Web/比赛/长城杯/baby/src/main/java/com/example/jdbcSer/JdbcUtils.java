package com.example.jdbcSer;

import java.sql.*;

public class JdbcUtils {
    private static Connection conn = null;

    JdbcUtils(String URL, String NAME, String PWD) throws Exception {
        Class.forName("com.mysql.jdbc.Driver");
        conn = DriverManager.getConnection(URL, NAME, PWD);
    }
    public int insert(UserBean user) throws Exception{
        String sql = "insert into user_data values(?,?)";
        PreparedStatement ptmt = conn.prepareStatement(sql);
        ptmt.setString(1,user.getUsername());
        ptmt.setString(2,user.getPassword());
        return  ptmt.executeUpdate();
    }
    public ResultSet select(UserBean user) throws Exception{
        String sql = "select count(*) from user_data where username=? and password=?";
        PreparedStatement ptmt = conn.prepareStatement(sql);
        ptmt.setString(1,user.getUsername());
        ptmt.setString(2,user.getPassword());
        return ptmt.executeQuery();
    }

    public ResultSet selectAll() throws Exception {
        String sql = "select * from user_data";
        PreparedStatement ptmt = conn.prepareStatement(sql);
        return ptmt.executeQuery();
    }
}
