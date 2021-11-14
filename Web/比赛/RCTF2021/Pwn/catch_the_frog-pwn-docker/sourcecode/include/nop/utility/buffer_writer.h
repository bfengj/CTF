/*
 * Copyright 2017-2019 The Native Object Protocols Authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

#ifndef LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_WRITER_H_
#define LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_WRITER_H_

#include <cstddef>
#include <cstdint>
#include <cstring>

#include <nop/base/encoding.h>
#include <nop/base/utility.h>

namespace nop {

// An efficient writer type that supports runtime serialization into a byte
// buffer. This writer improves efficiency by only performing bounds checks in
// the Prepare() method. This type is safe for use with the library-provided
// Serializer types, which predicate serialization on the result of Prepare().
// Use PedanticBufferWriter if your use case interacts with the writer directly
// and you need bounds checking in the Write() and Skip() methods.
class BufferWriter {
 public:
  BufferWriter() = default;
  BufferWriter(const BufferWriter&) = default;
  template <std::size_t Size>
  BufferWriter(std::uint8_t (&buffer)[Size]) : buffer_{buffer}, size_{Size} {}
  BufferWriter(std::uint8_t* buffer, std::size_t size)
      : buffer_{buffer}, size_{size} {}
  BufferWriter(void* buffer, std::size_t size)
      : buffer_{static_cast<std::uint8_t*>(buffer)}, size_{size} {}

  BufferWriter& operator=(const BufferWriter&) = default;

  Status<void> Prepare(std::size_t size) {
    if (index_ + size > size_)
      return ErrorStatus::WriteLimitReached;
    else
      return {};
  }

  Status<void> Write(std::uint8_t byte) { return Write(&byte, &byte + 1); }

  template <typename T, typename Enable = EnableIfArithmetic<T>>
  Status<void> Write(const T* begin, const T* end) {
    const std::size_t element_size = sizeof(T);
    const std::size_t length = end - begin;
    const std::size_t length_bytes = length * element_size;

    std::memcpy(&buffer_[index_], begin, length_bytes);
    index_ += length_bytes;
    return {};
  }

  Status<void> Skip(std::size_t padding_bytes,
                    std::uint8_t padding_value = 0x00) {
    std::memset(&buffer_[index_], padding_value, padding_bytes);
    index_ += padding_bytes;
    return {};
  }

  std::size_t size() const { return index_; }
  std::size_t capacity() const { return size_; }

 private:
  std::uint8_t* buffer_{nullptr};
  std::size_t size_{0};
  std::size_t index_{0};
};

}  // namespace nop

#endif  // LIBNOP_INCLUDE_NOP_UTILITY_BUFFER_WRITER_H_
