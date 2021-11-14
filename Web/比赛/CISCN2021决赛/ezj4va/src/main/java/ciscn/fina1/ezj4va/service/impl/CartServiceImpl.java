package ciscn.fina1.ezj4va.service.impl;

import ciscn.fina1.ezj4va.domain.Cart;
import ciscn.fina1.ezj4va.service.CartService;
import ciscn.fina1.ezj4va.utils.Deserializer;
import java.math.BigDecimal;
import java.util.Map;

public class CartServiceImpl implements CartService {
    private CartServiceImpl(){}


    private static CartService cartService=new CartServiceImpl();

    public static CartService getInstance() {
        return cartService;
    }

    @Override
    public Cart addToCart(String skus, String oldCartStr) throws Exception {
        Cart toAdd =(Cart) Deserializer.deserialize(skus);
        Cart cart=null;
        if(oldCartStr!=null)
            cart= (Cart) Deserializer.deserialize(oldCartStr);
        if(cart==null)
            cart=new Cart();

        if(toAdd.getSkuDescribe()!=null){
            Map skuDescribe = cart.getSkuDescribe();
            for(Map.Entry<String,Object> entry:toAdd.getSkuDescribe().entrySet()){
                skuDescribe.put(entry.getKey(),entry.getValue());
            }
        }

        if(toAdd.getSkuPrice()!=null){
            Map<String, BigDecimal> skuPrice = cart.getSkuPrice();
            for(Map.Entry<String, BigDecimal> entry:toAdd.getSkuPrice().entrySet()){
                String key = entry.getKey();
                BigDecimal oldPrice=skuPrice.getOrDefault(key,new BigDecimal("0"));
                skuPrice.put(key,entry.getValue().add(oldPrice));
            }
        }
        return cart;
    }

    @Override
    public Cart query(String oldCart) throws Exception{
        return (Cart) Deserializer.deserialize(oldCart);
    }

    @Override
    public Cart remove(String skus, String oldCartStr) throws Exception{
        Cart toRemove =(Cart) Deserializer.deserialize(skus);
        Cart cart=null;
        if(oldCartStr!=null)
            cart= (Cart) Deserializer.deserialize(oldCartStr);
        if(cart==null)
            cart=new Cart();

        if(toRemove.getSkuDescribe()!=null){
            Map skuDescribe = cart.getSkuDescribe();
            for(String key:toRemove.getSkuDescribe().keySet()){
                skuDescribe.remove(key);
            }
        }

        if(toRemove.getSkuPrice()!=null){
            Map<String, BigDecimal> skuPrice = cart.getSkuPrice();
            for(String key:toRemove.getSkuPrice().keySet()){
                skuPrice.remove(key);
            }
        }
        return cart;
    }
}
