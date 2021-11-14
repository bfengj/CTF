package com.example.jdbcSer;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;

@SpringBootApplication
public class Application {
	public void addResourceHandlers(ResourceHandlerRegistry registry) {
		registry
				.addResourceHandler("/**")
				.addResourceLocations("classpath:/META-INF/resources/", "classpath:/resources/", "classpath:/static/", "classpath:/public/");

	}
	public static void main(String[] args) {
		SpringApplication.run(Application.class, args);
	}
}
