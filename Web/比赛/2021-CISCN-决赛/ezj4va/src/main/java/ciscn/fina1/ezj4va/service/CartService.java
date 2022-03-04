package ciscn.fina1.ezj4va.service;

import ciscn.fina1.ezj4va.domain.Cart;

public interface CartService {

    Cart addToCart(String skus, String oldCartStr) throws Exception;

    Cart query(String oldCart) throws Exception;

    Cart remove(String skus, String oldCartStr) throws Exception;
}
