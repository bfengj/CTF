#!/bin/bash
# Simulates the latency in real world.
tc qdisc add dev eth0 root netem delay 100ms 10ms; npm run start