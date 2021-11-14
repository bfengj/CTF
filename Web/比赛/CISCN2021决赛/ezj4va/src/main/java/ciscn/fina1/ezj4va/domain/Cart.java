package ciscn.fina1.ezj4va.domain;

import java.io.Serializable;
import java.math.BigDecimal;
import java.util.HashMap;
import java.util.Map;

public class Cart implements Serializable {
    private static final long serialVersionUID = 512L;

    Map<String,Object> skuDescribe;
    Map<String,BigDecimal> skuPrice;

    BigDecimal totalPrice;
    BigDecimal payPrice;
    BigDecimal couponPirce;

    public Cart(){
        skuDescribe=new HashMap<>();
        skuPrice=new HashMap<>();
    }

    public Map<String, Object> getSkuDescribe() {
        return skuDescribe;
    }

    public void setSkuDescribe(Map<String, Object> skuDescribe) {
        this.skuDescribe = skuDescribe;
    }

    public Map<String, BigDecimal> getSkuPrice() {
        return skuPrice;
    }

    public void setSkuPrice(Map<String, BigDecimal> skuPrice) {
        this.skuPrice = skuPrice;
    }

    public BigDecimal getTotalPrice() {
        return totalPrice;
    }

    public void setTotalPrice(BigDecimal totalPrice) {
        this.totalPrice = totalPrice;
    }

    public BigDecimal getPayPrice() {
        return payPrice;
    }

    public void setPayPrice(BigDecimal payPrice) {
        this.payPrice = payPrice;
    }

    public BigDecimal getCouponPirce() {
        return couponPirce;
    }

    public void setCouponPirce(BigDecimal couponPirce) {
        this.couponPirce = couponPirce;
    }


}
