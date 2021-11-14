#include <string>

enum class Command{
    kFeed,
    kCatch,
    kName,
    kCheck,
    kFree,
};

struct Packet{
    Command cmd;
    size_t index;
    size_t size;
    std::string name;
    NOP_STRUCTURE(Packet, cmd, index, size, name);
};
